<?php

namespace WisamAlhennawi\LaraFormsBuilder\Traits;

trait HasTabs
{
    use MultiStepForm;

    public string $activeTab = '';

    public bool $hasTabs = true;

    public bool $isMultiStep = false;

    protected function initActiveTab($value)
    {
        // example of value: 'user-details' => 'InitUserDetailsTab'
        $functionName = 'Init'.str_replace('-', '', ucwords($value, '-')).'Tab';
        if (method_exists($this, $functionName)) {
            $this->{$functionName}($value);
        }
        if ($this->isMultiStepForm()) {
            $this->setActiveStepNumber($value);
        }
    }

    public function updatedActiveTab($value)
    {
        $this->initActiveTab($value);
    }
}
