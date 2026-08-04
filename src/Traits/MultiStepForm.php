<?php

namespace WisamAlhennawi\LaraFormsBuilder\Traits;

trait MultiStepForm
{
    public int $activeStepNumber = 1;

    public array $steps = [];

    public bool $showStepNumber = false;

    public bool $multiStepNavigationIconsEnabled = false;

    /**
     * Multi-step navigation options — set per-form via mountForm([...]) or as
     * class properties. Create mode is always a strict forward wizard, so
     * jumping, error badges, and Back/Jump validation are auto-disabled there;
     * these options only take effect in view/edit mode.
     */
    public bool $isJumpingBetweenStepsEnabled = false;

    public bool $shouldValidateCurrentStepOnNext = true;

    public bool $shouldValidateCurrentStepOnPrevious = false;

    public bool $shouldValidateCurrentStepOnJump = true;

    // Per-step validity snapshot [ 'step-key' => bool ] (true = valid); drives the nav error indicators. Filled on navigation except in create mode.
    public array $stepStatuses = [];

    /**
     * Initialize the steps
     */
    protected function initSteps(array $steps = []): void
    {
        if ($steps !== []) {
            $this->steps = $steps;

            return;
        }

        $this->steps = collect($this->fields)->map(function ($tab) {
            return [
                'key' => $tab['key'],
                'title' => $tab['title'],
                'fields' => $this->extractStepFields($tab),
            ];
        })->toArray();
    }

    /**
     * Extract the flat field list for a single tab definition.
     * Supports both a single group (content['fields']) and multiple groups (content is an array of groups, each with its own 'fields').
     */
    protected function extractStepFields(array $tab): array
    {
        // Single group: content has a 'fields' array directly
        if (isset($tab['content']['fields']) && is_array($tab['content']['fields'])) {
            return $tab['content']['fields'];
        }

        // Multiple groups: merge every group's 'fields'
        $fields = [];
        if (is_array($tab['content'] ?? null)) {
            foreach ($tab['content'] as $group) {
                if (is_array($group) && isset($group['fields']) && is_array($group['fields'])) {
                    $fields = array_merge($fields, $group['fields']);
                }
            }
        }

        return $fields;
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
     * Resolve the step key for a 1-based step number.
     * $this->steps is a 0-based array, activeStepNumber is 1-based, so the
     * mapping (and only here) crosses those two index spaces. Returns null
     * when the number falls outside the available steps.
     */
    protected function stepKeyAt(int $stepNumber): ?string
    {
        return $this->steps[$stepNumber - 1]['key'] ?? null;
    }

    /**
     * previous step
     */
    public function previousStep(): void
    {
        if (($key = $this->stepKeyAt($this->activeStepNumber - 1)) !== null) {
            $this->changeStep($key, 'backward');
        }
    }

    /**
     * next step
     */
    public function nextStep(): void
    {
        if (($key = $this->stepKeyAt($this->activeStepNumber + 1)) !== null) {
            $this->changeStep($key, 'forward');
        }
    }

    /**
     * Jump directly to an arbitrary step by key.
     */
    public function goToStep(string $stepKey): void
    {
        if ($this->canJumpBetweenSteps()) {
            $this->changeStep($stepKey, 'jump');
        }
    }

    /**
     * Check if free step jumping is allowed. Used by goToStep() and
     * the step-nav view.
     */
    public function canJumpBetweenSteps(): bool
    {
        return $this->isJumpingBetweenStepsEnabled && $this->mode !== 'create';
    }

    /**
     * The single navigation pipeline shared by next/previous/goToStep.
     * Runs the guard hook canLeaveStep(), performs the switch, then the after-change hook onStepChanged().
     */
    protected function changeStep(string $to, string $direction): void
    {
        $from = $this->activeTab;

        if ($to === '' || $to === $from) {
            return;
        }

        if (! $this->canLeaveStep($from, $to, $direction)) {
            return;
        }

        $this->activeTab = $to;
        $this->setActiveStepNumber($to);

        // Leaving a step in any non-forward direction exits confirmation mode.
        if ($direction !== 'forward' && $this->mode === 'confirm') {
            $this->mode = null;
        }

        $this->onStepChanged($from, $to, $direction);
    }

    /**
     * Guard: may the user leave the current step for $to?
     * Default: when validation is required for this direction, validate the
     * step's own base rules and run extraValidate(); return false to block.
     * Override for custom leave rules.
     */
    protected function canLeaveStep(string $from, string $to, string $direction): bool
    {
        if (! $this->shouldValidateOnLeave($direction)) {
            return true;
        }

        if ($this->scrollToFirstError) {
            $this->dispatch('scroll-to-first-error');
        }

        return $this->validateCurrentStep();
    }

    /**
     * Validate the CURRENT step: runs Livewire's validate() (which throws + renders errors on base-rule failure) and extraValidate() (which writes the error/warning bag).
     */
    protected function validateCurrentStep(): bool
    {
        $validatedData = $this->validate($this->stepValidationRules($this->activeTab))['formProperties'] ?? [];

        return $this->extraValidate($validatedData);
    }

    /**
     * Hook: runs right after the active step has changed.
     * Default: regenerate any derived step content, refresh the status snapshot, and scroll to the top of the form.
     */
    protected function onStepChanged(string $from, string $to, string $direction): void
    {
        $this->refreshSteps($to, $from, $direction);
        $this->refreshStepStatuses();
        $this->dispatch('scroll-to-top-form');
    }

    /**
     * Hook: regenerate step content that depends on another step's answers.
     * Default no-op. Override and call rebuildStepFields() to rebuild only the
     * affected step (leaving other steps' fields untouched).
     */
    protected function refreshSteps(string $to, string $from, string $direction): void {}

    /**
     * Rebuild a single step's definition in place — updates only the matching
     * tab in $this->fields and its $this->steps entry, so every other step keeps
     * its current (possibly repeater-expanded) fields. Prefer this over a whole
     * $this->fields rebuild.
     */
    protected function rebuildStepFields(string $stepKey, array $tabDefinition): void
    {
        foreach ($this->fields as $index => $tab) {
            if (($tab['key'] ?? null) === $stepKey) {
                $this->fields[$index] = $tabDefinition;
                break;
            }
        }

        foreach ($this->steps as $index => $step) {
            if (($step['key'] ?? null) === $stepKey) {
                $this->steps[$index] = [
                    'key' => $tabDefinition['key'],
                    'title' => $tabDefinition['title'] ?? ($step['title'] ?? null),
                    'fields' => $this->extractStepFields($tabDefinition),
                ];
                break;
            }
        }
    }

    /**
     * Field keys belonging to a step, resolved by step key.
     */
    protected function stepFieldKeys(string $stepKey): array
    {
        foreach ($this->steps as $step) {
            if (($step['key'] ?? null) === $stepKey) {
                return array_keys($step['fields'] ?? []);
            }
        }

        return [];
    }

    /**
     * The subset of $this->rules that applies to a given step's fields.
     */
    protected function stepValidationRules(string $stepKey): array
    {
        return array_intersect_key(
            $this->rules,
            array_flip(array_map(
                fn ($key) => 'formProperties.'.$key,
                $this->stepFieldKeys($stepKey)
            ))
        );
    }

    /**
     * Whether a step is valid — pure/read-only (no throw, no error bag, no state mutation),
     * So it is safe to call for every step from anywhere (drives the nav error indicators).
     */
    protected function isStepValid(string $stepKey): bool
    {
        return ! app('validator')->make(
            ['formProperties' => $this->formProperties],
            $this->stepValidationRules($stepKey)
        )->fails();
    }

    /**
     * Build the per-step validity snapshot: [ 'step-key' => bool ] (true = valid).
     */
    protected function resolveStepStatuses(): array
    {
        $statuses = [];
        foreach ($this->steps as $step) {
            $statuses[$step['key']] = $this->isStepValid($step['key']);
        }

        return $statuses;
    }

    /**
     * Refresh $this->stepStatuses (or clear it when disabled).
     */
    protected function refreshStepStatuses(): void
    {
        // Skip on create mode — a fresh, untouched form should not show every step red.
        $this->stepStatuses = $this->mode !== 'create'
            ? $this->resolveStepStatuses()
            : [];
    }

    /**
     * Validate every step before saving so a rule on a non-active step cannot be
     * bypassed. On the first invalid step, routes to it and surfaces its errors,
     * then returns false to block the save; returns true when all steps pass.
     * Called by submit() for multi-step forms.
     */
    protected function validateAllStepsBeforeSave(): bool
    {
        $statuses = $this->resolveStepStatuses();

        // Keep the nav badges in sync with this save-time validation.
        if ($this->mode !== 'create') {
            $this->stepStatuses = $statuses;
        }

        $invalidSteps = array_keys(array_filter($statuses, fn ($valid) => ! $valid));

        if ($invalidSteps === []) {
            return true;
        }

        // Route to the first invalid step, then surface its errors there
        // (extraValidate() keys off the now-active step).
        $target = $invalidSteps[0];

        if ($this->activeTab !== $target) {
            $from = $this->activeTab;
            $this->activeTab = $target;
            $this->setActiveStepNumber($target);
            $this->refreshSteps($target, $from, 'jump');
        }

        $this->validateCurrentStep();

        return false;
    }

    /**
     * Whether leaving the current step validates it first, per direction.
     */
    protected function shouldValidateOnLeave(string $direction): bool
    {
        // Create mode is a strict forward wizard: validate only on Next; Back and
        // Jump validation are view/edit-only (and jumping is disabled on create).
        return match ($direction) {
            'forward' => $this->shouldValidateCurrentStepOnNext,
            'backward' => $this->mode !== 'create' && $this->shouldValidateCurrentStepOnPrevious,
            'jump' => $this->mode !== 'create' && $this->shouldValidateCurrentStepOnJump,
            default => true,
        };
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

    /**
     * Allows customizing the label of the "Previous Step" button.
     * Can be overridden in the form controller.
     */
    public function getPreviousStepLabel(): string
    {
        return __('Previous Step');
    }

    /**
     * Allows customizing the label of the "Next Step" button.
     * Can be overridden in the form controller.
     */
    public function getNextStepLabel(): string
    {
        return __('Next Step');
    }

    /**
     * Allows customizing the SVG icon for the "Previous Step" button.
     * Can be overridden in the form controller. Return SVG markup or null.
     * When multiStepNavigationIconsEnabled is true, returns a default chevron-left icon if not overridden.
     */
    public function getPreviousStepIcon(): ?string
    {
        if ($this->multiStepNavigationIconsEnabled) {
            return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>';
        }

        return null;
    }

    /**
     * Allows customizing the SVG icon for the "Next Step" button.
     * Can be overridden in the form controller. Return SVG markup or null.
     * When multiStepNavigationIconsEnabled is true, returns a default chevron-right icon if not overridden.
     */
    public function getNextStepIcon(): ?string
    {
        if ($this->multiStepNavigationIconsEnabled) {
            return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>';
        }

        return null;
    }

    /**
     * SVG icon shown on invalid steps in the nav (uses currentColor, so the
     * .lfb-step-nav-title-error styling colours it). Can be overridden in the
     * form controller; return null to show no icon.
     */
    public function getStepErrorIcon(): ?string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-5 h-5 flex-shrink-0"><path d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" /></svg>';
    }
}
