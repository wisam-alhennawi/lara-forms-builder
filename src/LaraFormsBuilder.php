<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Illuminate\Support\Str;
use Lang;
use Route;

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

    /**
     * get field keys from fields array
     *
     * @return array
     */
    public function getFieldKeys()
    {
        // get all keys from fields array
        return array_map(function ($field) {
            return $field['key'];
        }, $this->getFieldsFlat());
    }

    /**
     * get all fields from fields array as flat array
     *
     * @return array
     */
    public function getFieldsFlat()
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
     * @return string
     */
    private function getFieldRules($field, $key, $modelRules)
    {
        $fieldRules = '';
        // check if the field has rules or the model has rules for this field
        if (isset($field['rules'])) {
            $fieldRules = $field['rules'];
        } elseif (isset($modelRules[$key])) {
            $fieldRules = $modelRules[$key];
        } else {
            $fieldRules = '';
        }

        return $fieldRules;
    }

    /**
     * @return array
     */
    private function getFieldRulesAndValidationAttributes()
    {
        $modelRules = get_class($this->model)::$rules ?? [];
        $fieldRules = [];
        $fieldValidationAttributes = [];
        foreach ($this->fields() as $key => $field) {
            if (! isset($this->hasTabs) || ! $this->hasTabs) {
                if (is_numeric($key) && isset($field['fields'])) {
                    foreach ($field['fields'] as $key => $field) {
                        $fieldRules[$key] = $this->getFieldRules($field, $key, $modelRules);
                        $fieldValidationAttributes[$key] = $this->getFieldValidationAttribute($field, $key);
                    }
                } else {
                    $fieldRules[$key] = $this->getFieldRules($field, $key, $modelRules);
                    $fieldValidationAttributes[$key] = $this->getFieldValidationAttribute($field, $key);
                }
            } else {
                // tabs
                $tabContents = $field['content'] ?? [];
                foreach ($tabContents as $tabKey => $tabContent) {
                    // check if the field is tab
                    if ($tabKey == 'fields' && is_array($tabContent)) {
                        foreach ($tabContent as $key => $field) {
                            $fieldRules[$key] = $this->getFieldRules($field, $key, $modelRules);
                            $fieldValidationAttributes[$key] = $this->getFieldValidationAttribute($field, $key);
                        }
                    } elseif (is_numeric($tabKey)) {
                        $fieldRules[$key] = $this->getFieldRules($tabContent, $tabKey, $modelRules);
                        $fieldValidationAttributes[$key] = $this->getFieldValidationAttribute($field, $key);
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
    protected function mountForm($model, $configurations = [])
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
    protected function beforeFormProperties()
    {
    }

    /**
     * Set form properties
     */
    protected function setFormProperties()
    {
        // get all fields
        $fields = $this->getFieldsFlat();
        // set field values
        foreach ($fields as $field) {
            if (filled($this->model) && $this->model->exists) {
                $this->{$field['key']} = isset($this->model->{$field['key']}) ? $this->model->{$field['key']} : null;
            } elseif (isset($field['field']['default'])) {
                $this->{$field['key']} = $field['field']['default'];
            } else {
                $this->{$field['key']} = null;
            }
            // define a property if field is search-picker (e.g.: foo -> foo_search_picker)
            if ($field['field']['type'] === 'search-picker') {
                $this->{$field['key'].'_search_picker'} = null;
            }
        }
    }

    /**
     * It can be used to change options, values, formats, etc. after setting the form properties
     */
    protected function afterFormProperties()
    {
    }

    /**
     * A Livewire component's render method gets called on the initial page load AND every subsequent component update.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('lara-forms-builder::form');
    }

    private function processSaveFunctions()
    {
        // saveFoo(), for all fields
        foreach ($this->getFieldKeys() as $fieldKey) {
            $function = 'save'.Str::of($fieldKey)->studly();
            $validated_data = $this->$fieldKey;
            if (method_exists($this, $function)) {
                $this->$function($validated_data);
            }
        }
    }

    /**
     * It can be used to add extra validation rules
     *
     * @return bool
     */
    protected function extraValidate($validated_data = [])
    {
        return true;
    }

    /**
     * Submit the form (validation, create or update the model, etc.)
     */
    protected function submit()
    {
        $validated_data = $this->validate();
        if (! $this->extraValidate($validated_data)) {
            return false;
        }

        if ($this->confirmBeforeSubmit && $this->mode != 'confirm') {
            foreach ($validated_data as $key => $value) {
                $this->model->$key = $value;
            }
            $this->mode = 'confirm';
            $this->emit('formMode', $this->mode);

            return false;
        }

        // create or update the model
        $this->success($validated_data);

        // it could be used to save relations or do other things after saving the model, saveFoo() method
        $this->processSaveFunctions();

        return true;
    }

    /**
     * Create or update a model
     */
    protected function success($model_fields_data)
    {
        filled($this->model) && $this->model->exists ? $this->onUpdateModel($model_fields_data) : $this->onCreateModel($model_fields_data);
    }

    /**
     * Create a model
     */
    protected function onCreateModel($validated_data)
    {
        $this->model = $this->model::create($validated_data);
    }

    /**
     * Update a model
     */
    protected function onUpdateModel($validated_data)
    {
        $this->model->update($validated_data);
    }

    /**
     * Reset the value of a field
     */
    public function resetValue($fieldKey)
    {
        $this->{$fieldKey} = null;
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
        if ($this->submit() && count($this->errorBag->getMessages()) == 0) {
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
            $this->dispatchBrowserEvent('banner-message', [
                'style' => 'success',
                'message' => $message,
            ]);
        }
    }

    public function cancelOrBack()
    {
        if ($this->mode == 'confirm') {
            $this->mode = null;
            $this->emit('formMode', $this->mode);

            return null;
        }

        return $this->cancel();
    }

    /**
     * Set the related value of selected search picker option
     */
    public function setSearchPickerValue($value, $key)
    {
        $this->$key = $value;
        $this->{$key.'_search_picker'} = null;
        if (isset($this->{Str::camel($key).'Options'})) {
            $this->reset(Str::camel($key).'Options');
        }
    }

    protected function searchPickerOptions($name, $value)
    {
        // call proper get***Options() function if field is search-picker
        if (str_contains($name, '_search_picker')) {
            foreach ($this->getFieldsFlat() as $fieldFlat) {
                if ($fieldFlat['field']['type'] === 'search-picker' && $name === $fieldFlat['key'].'_search_picker') {
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
        // set empty string to null
        if ($value === '') {
            $this->{$name} = null;
        }

        // search-picker
        $this->searchPickerOptions($name, $value);
    }

    /**
     * Get the default css classes for the wrapper of group of fields
     *
     * @return string
     */
    protected function getDefaultGroupWrapperClass()
    {
        return config('lara-forms-builder.default_group_wrapper_class');
    }

    /**
     * Get the default css classes for the wrapper of field
     *
     * @return string
     */
    protected function getDefaultFieldWrapperClass()
    {
        return config('lara-forms-builder.default_field_wrapper_class');
    }

    /**
     * Get the css classes for footer buttons wrapper
     *
     * @return string
     */
    protected function getFooterButtonsWrapperClasses()
    {
        return config('lara-forms-builder.footer_buttons_wrapper_classes');
    }

    /**
     * Get the css classes for primary button
     *
     * @return string
     */
    protected function getPrimaryButtonClasses()
    {
        return config('lara-forms-builder.primary_button_classes');
    }

    /**
     * Get the css classes for secondary button
     *
     * @return string
     */
    protected function getSecodaryButtonClasses()
    {
        return config('lara-forms-builder.secondary_button_classes');
    }

    /**
     * Get the css classes for the wrapper of error messages of card fields
     *
     * @return string
     */
    protected function getDefaultCardFieldErrorWrapperClasses()
    {
        return config('lara-forms-builder.card_field_error_wrapper_classes');
    }
}
