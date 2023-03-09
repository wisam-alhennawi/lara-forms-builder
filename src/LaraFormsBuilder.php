<?php

namespace WisamAlhennawi\LaraFormsBuilder;

use Illuminate\Support\Str;
use Lang;
use Route;

trait LaraFormsBuilder
{
    public $model;

    public $mode;

    public $submitButtonLabel;

    public $cancelButtonLabel;

    public $headView;

    /**
     * @return array
     */
    public function getFieldKeys()
    {
        $fieldKeys = [];
        foreach ($this->fields() as $key => $field) {
            if (is_numeric($key) && isset($field['fields'])) {
                foreach ($field['fields'] as $key => $field) {
                    $fieldKeys[] = $key;
                }
            } else {
                $fieldKeys[] = $key;
            }
        }

        return $fieldKeys;
    }

    /**
     * @param $field array
     * @param $key string
     * @param $modelRules array
     *
     * @return string
     */
    private function getfieldRules($field, $key, $modelRules){
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
            if (is_numeric($key) && isset($field['fields'])) {
                foreach ($field['fields'] as $key => $field) {
                    $fieldRules[$key] = $this->getfieldRules($field, $key, $modelRules);
                    $fieldValidationAttributes[$key] = $field['label'] ?? $key;
                }
            } else {
                $fieldRules[$key] = $this->getfieldRules($field, $key, $modelRules);
                $fieldValidationAttributes[$key] = $field['label'] ?? $key;
            }
        }

        return [$fieldRules, $fieldValidationAttributes];
    }

    /**
     * It should be called in mount method which runs once, immediately after the component is instantiated, but before render() is called. This is only called once on initial page load and never called again, even on component refreshes
     * It will set the model, mode, submitButtonLabel, cancelButtonLabel, form properties
     *
     * @param $model
     */
    protected function mountForm($model)
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

        $this->beforeFormProperties();
        $this->setFormProperties();
        $this->afterFormProperties();
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
        // set field values
        $fieldKeys = $this->getFieldKeys();
        // set field values
        foreach ($fieldKeys as $fieldKey) {
            if (filled($this->model) && $this->model->exists) {
                $this->$fieldKey = isset($this->model->$fieldKey) ? $this->model->$fieldKey : null;
            } else {
                $this->$fieldKey = null;
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

    /**
     * Submit the form (validation, create or update the model, etc.)
     */
    protected function submit()
    {
        $validated_data = $this->validate();

        // create or update the model
        $this->success($validated_data);

        // it could be used to save relations or do other things after saving the model, saveFoo() method
        foreach ($this->getFieldKeys() as $fieldKey) {
            $function = 'save'.Str::of($fieldKey)->studly();
            $validated_data = $this->$fieldKey;
            if (method_exists($this, $function)) {
                $this->$function($validated_data);
            }
        }
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
     * After clicking on the submit button (submit the form, show success message and callback)
     */
    public function checkAndSave()
    {
        $this->submit();
        if (count($this->errorBag->getMessages()) == 0) {
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
        // if you want to customize the success message, you should add the custom message key entry to the lang file (Example: "A new forestryPoolMember has benn created successfully.") or override the method
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

        session()->flash('flash.banner', $message);
        session()->flash('flash.bannerStyle', 'success');
    }

    /**
     * Get the default css classes for the wrapper of group of fields
     *
     * @return string
     */
    protected function getDefaultGroupWrapperClass()
    {
        // TODO : add to config
        return 'grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2';
    }
}
