<?php

namespace WisamAlhennawi\LaraFormsBuilder\Traits;

trait MultiStepForm
{
    public int $activeStepNumber = 1;

    public array $steps = [];

    /**
     * Initialize the steps
     */
    protected function initSteps(array $steps = []): void
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
     */
    protected function setActiveStepNumber($stepKey): void
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
    public function previousStep(): void
    {
        $this->activeStepNumber--;
        $this->activeTab = $this->steps[$this->activeStepNumber - 1]['key'];
        if ($this->mode == 'confirm') {
            $this->mode = null;
        }
        $this->dispatch('scroll-to-top-form');
    }

    /**
     * next step
     */
    public function nextStep()
    {
        if ($this->scrollToFirstError) {
            $this->dispatch('scroll-to-first-error');
        }

        // validate the current step
        $validatedData = $this->validate(
            array_intersect_key(
                $this->rules,
                array_flip(
                    array_map(fn ($element) => 'formProperties.'.$element,
                        array_keys($this->steps[$this->activeStepNumber - 1]['fields'])
                    )
                )
            )
        );

        $validatedData = $validatedData['formProperties'];

        if (! $this->extraValidate($validatedData)) {
            return false;
        }
        $this->activeStepNumber = $this->activeStepNumber + 1;
        $this->activeTab = $this->steps[$this->activeStepNumber - 1]['key'];
        $this->dispatch('scroll-to-top-form');
    }

    /**
     * Get the css classes for the previous button
     */
    protected function getPreviousButtonClasses(): string
    {
        return config('lara-forms-builder.previous_button_classes');
    }

    /**
     * Get the css classes for the next button
     */
    protected function getNextButtonClasses(): string
    {
        return config('lara-forms-builder.next_button_classes');
    }
}
