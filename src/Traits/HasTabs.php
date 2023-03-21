<?php

namespace WisamAlhennawi\LaraFormsBuilder\Traits;

trait HasTabs
{
    public $activeTab = '';
    public $hasTabs = true;

    protected function initActiveTab($value)
    {
        // example of value: 'user-details' => 'InitUserDetailsTab'
        $functionName = 'Init'.str_replace('-', '', ucwords($value, '-')).'Tab';
        if (method_exists($this, $functionName)) {
            $this->{$functionName}($value);
        }
    }

    protected function updatedActiveTab($value)
    {
        $this->initActiveTab($value);
    }
}
