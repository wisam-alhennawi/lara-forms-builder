@php
    $position = $position ?? 'top';
@endphp
<div class="{{ $position === 'top' ? $this->getHeaderButtonsWrapperClasses() : $this->getFooterButtonsWrapperClasses() }}">
    <div class="lfb-buttons @if($this->isMultiStepForm() && $this->activeStepNumber() > 1)lfb-multi-step-buttons @endif">
        @if ($this->isMultiStepForm())
            @if ($this->activeStepNumber() > 1)
                <button wire:click="previousStep" class="{{$this->getPreviousButtonClasses()}} lfb-prev-multi-step-button ">
                    @if(method_exists($this, 'getPreviousStepIcon') && $this->getPreviousStepIcon())
                        {!! $this->getPreviousStepIcon() !!}
                    @endif
                    <span class="lbf-button-label">
                        {{ method_exists($this, 'getPreviousStepLabel') && $this->getPreviousStepLabel() ? $this->getPreviousStepLabel() : __('Previous Step') }}
                    </span>
                </button>
            @endif
            @if ($this->activeStepNumber() != $this->totalSteps())
                <button wire:click="nextStep" class="{{$this->getNextButtonClasses()}} lfb-next-multi-step-button">
                    <span class="lbf-button-label">
                        {{ method_exists($this, 'getNextStepLabel') && $this->getNextStepLabel() ? $this->getNextStepLabel() : __('Next Step') }}
                    </span>
                    @if(method_exists($this, 'getNextStepIcon') && $this->getNextStepIcon())
                        {!! $this->getNextStepIcon() !!}
                    @endif
                </button>
            @else
                @include('lara-forms-builder::includes.submit-button')
            @endif
        @else
            <button wire:click="cancelOrBack" class="{{$this->getSecondaryButtonClasses()}}">
                {{ (isset($mode) && $mode == 'confirm') ? __('Back') : $cancelButtonLabel }}
            </button>
            @if (isset($mode) && $mode == 'view')
                @if ($this->canSubmit())
                    <button wire:click="switchToEditMode" class="{{$this->getPrimaryButtonClasses()}}">
                        {{ __('Edit') }}
                    </button>
                @endif
            @else
                @include('lara-forms-builder::includes.submit-button')
            @endif
        @endif
    </div>
</div>
