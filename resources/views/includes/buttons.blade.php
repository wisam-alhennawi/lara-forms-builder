<div class="{{ $this->getFooterButtonsWrapperClasses() }}">
    <div class="lfb-buttons @if($this->isMultiStepForm() && $this->activeStepNumber() > 1)lfb-multi-step-buttons @endif">
        @if ($this->isMultiStepForm())
            @if ($this->activeStepNumber() > 1)
                <button wire:click="previousStep" class="{{$this->getPreviousButtonClasses()}}">
                    {{ __('Previous Step') }}
                </button>
            @endif
            @if ($this->activeStepNumber() != $this->totalSteps())
                <button wire:click="nextStep" class="{{$this->getNextButtonClasses()}}">
                    {{ __('Next Step') }}
                </button>
            @else
                @include('lara-forms-builder::includes.submit-button')
            @endif
        @else
            <button wire:click="cancelOrBack" class="{{$this->getSecondaryButtonClasses()}}">
                {{ (isset($mode) && $mode == 'confirm') ? __('Back') : $cancelButtonLabel }}
            </button>
            @if (isset($mode) && $mode == 'view')
                @can('update', $model)
                <button wire:click="switchToEditMode" class="{{$this->getPrimaryButtonClasses()}}">
                    {{ __('Edit') }}
                </button>
                @endcan
            @else
                @include('lara-forms-builder::includes.submit-button')
            @endif
        @endif
    </div>
</div>
