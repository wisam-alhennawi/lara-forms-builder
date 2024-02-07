<?php

namespace WisamAlhennawi\LaraFormsBuilder\Traits;

trait MultiStepForm
{
    public $activeStepNumber = 1;

    public $steps = [];

    /**
     * Initialize the steps
     *
     * @param  array  $steps
     */
    protected function initSteps($steps = [])
    {
        if (empty($steps)) {
            $this->steps = collect($this->fields)->map(function ($tab) {
                return [
                    'key' => $tab['key'],
                    'title' => $tab['title'],
                    'fields' => $tab['content']['fields'],
                ];
            })->toArray();
        } else {
            $this->steps = $steps;
        }
    }

    /**
     * Set the active step number
     *
     * @param  $value
     */
    protected function setActiveStepNumber($stepKey)
    {
        // set the active step number
        foreach ($this->steps as $index => $step) {
            if ($step['key'] === $stepKey) {
                $this->activeStepNumber = $index + 1;
                break;
            }
        }
    }

    /**
     * Check if the form is a multi step form
     */
    public function isMultiStepForm(): bool
    {
        return $this->isMultiStep;
    }

    /**
     * Get the active step number
     */
    public function activeStepNumber(): int
    {
        return $this->activeStepNumber;
    }

    /**
     * Total steps in the form
     */
    public function totalSteps(): int
    {
        return count($this->steps);
    }

    /**
     * previous step
     */
    public function previousStep()
    {
        $this->activeStepNumber--;
        $this->activeTab = $this->steps[$this->activeStepNumber - 1]['key'];
    }

    /**
     * next step
     */
    public function nextStep()
    {
        // validate the current step
        $validated_data = $this->validate(array_intersect_key($this->rules, array_flip(array_keys($this->steps[$this->activeStepNumber - 1]['fields']))));
        if (! $this->extraValidate($validated_data)) {
            return false;
        }
        $this->activeStepNumber = $this->activeStepNumber + 1;
        $this->activeTab = $this->steps[$this->activeStepNumber - 1]['key'];
    }

    /**
     * Get the css classes for the previous button
     *
     * @return string
     */
    protected function getPreviousButtonClasses()
    {
        return config('lara-forms-builder.previous_button_classes');
    }

    /**
     * Get the css classes for the next button
     *
     * @return string
     */
    protected function getNextButtonClasses()
    {
        return config('lara-forms-builder.next_button_classes');
    }
}