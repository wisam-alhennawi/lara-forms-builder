<?php

namespace WisamAlhennawi\LaraFormsBuilder\Traits;

trait HasTabs
{
    use MultiStepForm;

    public string $activeTab = '';

    public bool $hasTabs = true;

    /**
     * If true, shows the buttons also above the tabs/multistep form.
     * Set from the controller via mountForm([... 'hasTopNavigation' => true ...])
     * Default: false (buttons only below)
     */
    public bool $hasTopNavigation = false;
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
