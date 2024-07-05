<?php

namespace WisamAlhennawi\LaraFormsBuilder\Traits;

use Illuminate\Support\Str;

trait FieldIndicator
{
    /**
     * Check if indicator should be displayed for the field or not based on the rules array or custom check
     *
     * @param string $fieldKey
     * @return bool
     */
    private function shouldDisplayIndicator($fieldKey): bool
    {
        return (
            isset($this->rules)
            && array_key_exists('formProperties.'.$fieldKey, $this->rules)
            && Str::contains($this->rules['formProperties.'.$fieldKey], 'required')
            // conditionl required rules should be handled in the method (shouldDisplayIndicatorBasedOnCustomCheck) if needed
            && ! Str::contains($this->rules['formProperties.'.$fieldKey], 'required_')
        )
        || $this->shouldDisplayIndicatorBasedOnCustomCheck($fieldKey);
    }

    /**
     * Display an indicator (*) for a required field in the form
     *
     * @param string $fieldKey
     * @return string|null
     */
    protected function fieldIndicator($fieldKey): ?string
    {
        return $this->shouldDisplayIndicator($fieldKey)
            ? '<sup>*</sup>'
            : null;
    }

    /**
     * Check if indicator should be displayed for the field based on a custom check (eg. conditional required rules like required_if)
     */
    protected function shouldDisplayIndicatorBasedOnCustomCheck($fieldKey): bool
    {
        return false;
    }
}