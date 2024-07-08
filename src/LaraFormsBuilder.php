<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait LaraFormsBuilder
{
    public $model;

    public $mode;

    public $confirmBeforeSubmit = false;

    public $submitButtonLabel;

    public $cancelButtonLabel;

    public $headView;

    public $fields;

    public bool $hasSession = true;

    public bool $disableSaveButton = false;

    public array $rules;

    public array $validationAttributes;

    public string $customSuccessMessage = '';

    public array $formProperties = [];

    public $scrollToFirstError = false;

    /**
     * get field keys from fields array
     */
    public function getFieldKeys(): array
    {
        // get all keys from fields array
        return array_map(function ($field) {
            return $field['key'];
        }, $this->getFieldsFlat());
    }

    /**
     * get all fields from fields array as flat array
     */
    public function getFieldsFlat(): array
    {
        $fields = [];
        foreach ($this->fields() as $key => $field) {
            if (! isset($this->hasTabs) || ! $this->hasTabs) {
                if (is_numeric($key) && isset($field['fields'])) {
                    foreach ($field['fields'] as $k => $f) {
                        $fields[] = [
                            'key' => $k,
                            'field' => $f,
                        ];
                    }
                } else {
                    $fields[] = [
                        'key' => $key,
                        'field' => $field,
                    ];
                }
            } else {
                // tabs
                $tabFields = $field['content'] ?? [];
                foreach ($tabFields as $tabFieldKey => $tabFieldValue) {
                    // check if the field is tab
                    if ($tabFieldKey == 'fields' && is_array($tabFieldValue)) {
                        foreach ($tabFieldValue as $k => $f) {
                            $fields[] = [
                                'key' => $k,
                                'field' => $f,
                            ];
                        }
                    } elseif (is_numeric($tabFieldKey)) {
                        $fields[] = [
                            'key' => $tabFieldKey,
                            'field' => $tabFieldValue,
                        ];
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * @param  $field  array
     * @param  $key  string
     * @param  $modelRules  array
     */
    private function getFieldRules(array $field, string $key, array $modelRules): string
    {
        $fieldRules = '';

        // check if the field has rules or the model has rules for this field
        if (isset($field['rules'])) {
            $fieldRules = $field['rules'];
        } elseif (isset($modelRules[$key])) {
            $fieldRules = $modelRules[$key];
        }

        return $fieldRules;
    }

    private function getFieldRulesAndValidationAttributes(): array
    {
        $modelRules = get_class($this->model)::$rules ?? [];
        $fieldRules = [];
        $fieldValidationAttributes = [];
        foreach ($this->fields() as $key => $field) {
            if (! isset($this->hasTabs) || ! $this->hasTabs) {
                if (is_numeric($key) && isset($field['fields'])) {
                    foreach ($field['fields'] as $key => $field) {
                        $fieldRules['formProperties.'.$key] = $this->getFieldRules($field, $key, $modelRules);
                        $fieldValidationAttributes['formProperties.'.$key] = $this->getFieldValidationAttribute($field, $key);
                    }
                } else {
                    $fieldRules['formProperties.'.$key] = $this->getFieldRules($field, $key, $modelRules);
                    $fieldValidationAttributes['formProperties.'.$key] = $this->getFieldValidationAttribute($field, $key);
                }
            } else {
                // tabs
                $tabContents = $field['content'] ?? [];
                foreach ($tabContents as $tabKey => $tabContent) {
                    // check if the field is tab
                    if ($tabKey == 'fields' && is_array($tabContent)) {
                        foreach ($tabContent as $key => $field) {
                            $fieldRules['formProperties.'.$key] = $this->getFieldRules($field, $key, $modelRules);
                            $fieldValidationAttributes['formProperties.'.$key] = $this->getFieldValidationAttribute($field, $key);
                        }
                    } elseif (is_numeric($tabKey)) {
                        $fieldRules['formProperties.'.$key] = $this->getFieldRules($tabContent, $tabKey, $modelRules);
                        $fieldValidationAttributes['formProperties.'.$key] = $this->getFieldValidationAttribute($field, $key);
                    }
                }
            }
        }

        return [$fieldRules, $fieldValidationAttributes];
    }

    private function getFieldValidationAttribute($field, $key)
    {
        if (isset($field['validationAttribute'])) {
            return $field['validationAttribute'];
        }

        return $field['label'] ?? $key;
    }

    /**
     * Check if the form is a multi step form
     */
    public function isMultiStepForm(): bool
    {
        return isset($this->isMultiStep) && $this->isMultiStep;
    }

    /**
     * It should be called in mount method which runs once, immediately after the component is instantiated, but before render() is called. This is only called once on initial page load and never called again, even on component refreshes
     * It will set the model, mode, submitButtonLabel, cancelButtonLabel, form properties
     */
    protected function mountForm($model, $configurations = []): void
    {
        $this->model = $model;
        [$this->rules, $this->validationAttributes] = $this->getFieldRulesAndValidationAttributes();

        if (str_ends_with(Route::currentRouteName(), '.show') && ! $this->mode) {
            $this->mode = 'view';
        }
        // TODO : add configuration for submit and cancel button labels
        $this->cancelButtonLabel = $this->mode == 'view' ? __('Back') : __('Cancel');
        $this->submitButtonLabel = __('Save');

        ($this->mode == 'view') ? '' : ((filled($this->model) && $this->model->exists) ? $this->mode = 'update' : $this->mode = 'create');

        // loop through configurations and set them
        foreach ($configurations as $key => $value) {
            $this->{$key} = $value;
        }

        $this->beforeFormProperties();
        $this->setFormProperties();
        $this->afterFormProperties();

        $this->fields = $this->fields();

        if ($this->isMultiStepForm()) {
            $this->initSteps();
        }
    }

    /**
     * It can be used to set options, values, etc. before setting the form properties
     */
    protected function beforeFormProperties(): void {}

    /**
     * Set form properties
     */
    protected function setFormProperties(): void
    {
        // get all fields
        $fields = $this->getFieldsFlat();
        // set field values
        foreach ($fields as $field) {
            if (filled($this->model) && $this->model->exists) {
                $this->formProperties[$field['key']] = $this->model->{$field['key']} ?? null;
            } elseif (isset($field['field']['default'])) {
                $this->formProperties[$field['key']] = $field['field']['default'];
            } else {
                $this->formProperties[$field['key']] = null;
            }
            // define a property in formProperties array if field is search-picker (e.g.: foo -> foo_search_picker)
            if ($field['field']['type'] === 'search-picker') {
                $this->formProperties[$field['key'].'_search_picker'] = null;
            }
        }
    }

    /**
     * It can be used to change options, values, formats, etc. after setting the form properties
     */
    protected function afterFormProperties(): void {}

    /**
     * A Livewire component's render method gets called on the initial page load AND every subsequent component update.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('lara-forms-builder::form');
    }

    private function processSaveFunctions(): void
    {
        // saveFoo(), for all fields
        foreach ($this->getFieldKeys() as $fieldKey) {
            $function = 'save'.Str::of($fieldKey)->studly();
            if (method_exists($this, $function) && array_key_exists($fieldKey, $this->formProperties)) {
                $this->$function($this->formProperties[$fieldKey]);
            }
        }
    }

    /**
     * It can be used to add extra validation rules
     */
    protected function extraValidate(array $validatedData = []): bool
    {
        return true;
    }

    /**
     * Check if the user is authorized to submit the form
     */
    protected function canSubmit(): bool
    {
        return ($this->model->exists)
            ? auth()->user()->can('update', $this->model)
            : auth()->user()->can('create', $this->model::class);
    }

    /**
     * Submit the form (validation, create or update the model, etc.)
     */
    protected function submit(): bool
    {

        if (! $this->canSubmit()) {
            $this->addError('formSubmit', __('You are not authorized to perform this action.'));

            return false;
        }

        if ($this->scrollToFirstError) {
            $this->dispatch('scroll-to-first-error');
        }

        $validatedData = $this->validate();

        $validatedData = $validatedData['formProperties'];

        if (! $this->extraValidate($validatedData)) {
            return false;
        }

        if ($this->confirmBeforeSubmit && $this->mode != 'confirm') {
            foreach ($validatedData as $key => $value) {
                $this->model->$key = $value;
            }
            $this->mode = 'confirm';
            $this->dispatch('formMode', $this->mode);

            return false;
        }

        // create or update the model
        $this->success($validatedData);

        // it could be used to save relations or do other things after saving the model, saveFoo() method
        $this->processSaveFunctions();

        return true;
    }

    /**
     * Create or update a model
     */
    protected function success($model_fields_data): void
    {
        filled($this->model) && $this->model->exists ? $this->onUpdateModel($model_fields_data) : $this->onCreateModel($model_fields_data);
    }

    /**
     * Create a model
     */
    protected function onCreateModel($validatedData): void
    {
        $this->model = $this->model::create($validatedData);
    }

    /**
     * Update a model
     */
    protected function onUpdateModel($validatedData): void
    {
        $this->model->update($validatedData);
    }

    /**
     * Reset the value of a field
     */
    public function resetValue($fieldKey): void
    {
        $this->formProperties[$fieldKey] = null;
        $this->{$fieldKey.'_preview'} = null;
        if ($this->model) {
            $this->model->{$fieldKey} = null;
        }
    }

    /**
     * After clicking on the submit button (submit the form, show success message and callback)
     */
    public function checkAndSave()
    {
        if ($this->submit() && count($this->getErrorBag()->getMessages()) == 0) {
            $this->successMessage();
            $this->responseCallBack();
        }
    }

    /**
     * Switch to edit mode
     */
    public function switchToEditMode()
    {
        return redirect()->route(Str::plural(Str::lcfirst(class_basename(get_class($this->model)))).'.edit', $this->model);
    }

    /**
     * Display a (generic or custom) success message
     */
    protected function successMessage()
    {
        if ($this->customSuccessMessage != '') {
            $message = $this->customSuccessMessage;
        } else {
            $modelName = Str::lcfirst(class_basename(get_class($this->model)));
            $customMessageKey = 'A new '.$modelName.' has been created successfully.';
            $message = trans('A new entry has been created successfully.');
            if ($this->mode == 'update') {
                $customMessageKey = 'The '.$modelName.' has been updated successfully.';
                $message = trans('Changes were saved successfully.');
            }

            if (Lang::has($customMessageKey)) {
                $message = trans($customMessageKey);
            }
        }

        if ($this->hasSession) {
            session()->flash('flash.banner', $message);
            session()->flash('flash.bannerStyle', 'success');
        } else {
            $this->dispatch('banner-message',
                style: 'success',
                message: $message,
            );
        }
    }

    public function cancelOrBack()
    {
        if ($this->mode == 'confirm') {
            $this->mode = null;
            $this->dispatch('formMode', $this->mode);

            return null;
        }

        return $this->cancel();
    }

    /**
     * Set the related value of selected search picker option
     */
    public function setSearchPickerValue($value, $key): void
    {
        $this->formProperties[$key] = $value;
        $this->formProperties[$key.'_search_picker'] = null;
        if (isset($this->{Str::camel($key).'Options'})) {
            $this->reset(Str::camel($key).'Options');
        }
    }

    protected function searchPickerOptions($name, $value): void
    {
        // call proper get***Options() function if field is search-picker
        if (str_contains($name, '_search_picker')) {
            foreach ($this->getFieldsFlat() as $fieldFlat) {
                if ($fieldFlat['field']['type'] === 'search-picker' && $name === 'formProperties.'.$fieldFlat['key'].'_search_picker') {
                    $searchPickerTerm = trim($value);
                    $searchOptionsPropertyName = Str::camel($fieldFlat['key']).'Options';
                    if ($searchPickerTerm) {
                        $functionName = 'get'.ucfirst($searchOptionsPropertyName);
                        if (method_exists($this, $functionName)) {
                            $this->$searchOptionsPropertyName = $this->$functionName($searchPickerTerm);
                        }
                    } else {
                        $this->reset($searchOptionsPropertyName);
                    }
                }
            }
        }
    }

    public function updated($name, $value)
    {
        if ($name !== 'activeTab') {
            $propertyName = explode('.', $name)[1];

            // set empty string to null
            if ($value === '') {
                $this->formProperties[$propertyName] = null;
            }

            $propertyNameInCamelCase = Str::camel($propertyName);
            $updatedFunctionName = 'updated'.ucfirst($propertyNameInCamelCase);
            if (method_exists($this, $updatedFunctionName)) {
                $this->$updatedFunctionName($value);
            }

            // search-picker
            $this->searchPickerOptions($name, $value);
        }
    }

    /**
     * Get the default css classes for the wrapper of group of fields
     */
    protected function getDefaultGroupWrapperClass(): string
    {
        return config('lara-forms-builder.default_group_wrapper_class');
    }

    /**
     * Get the default css classes for the wrapper of field
     */
    protected function getDefaultFieldWrapperClass(): string
    {
        return config('lara-forms-builder.default_field_wrapper_class');
    }

    /**
     * Get the css classes for footer buttons wrapper
     */
    protected function getFooterButtonsWrapperClasses(): string
    {
        return config('lara-forms-builder.footer_buttons_wrapper_classes');
    }

    /**
     * Get the css classes for primary button
     */
    protected function getPrimaryButtonClasses(): string
    {
        return config('lara-forms-builder.primary_button_classes');
    }

    /**
     * Get the css classes for secondary button
     */
    protected function getSecondaryButtonClasses(): string
    {
        return config('lara-forms-builder.secondary_button_classes');
    }

    /**
     * Get the css classes for the wrapper of error messages of card fields
     */
    protected function getDefaultCardFieldErrorWrapperClasses(): string
    {
        return config('lara-forms-builder.card_field_error_wrapper_classes');
    }

    /**
     * Get the css classes for the wrapper error fields
     */
    protected function getDefaultFieldErrorWrapperClasses(): string
    {
        return config('lara-forms-builder.field_error_wrapper_classes');
    }
}
