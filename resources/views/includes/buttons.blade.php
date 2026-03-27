@php
    $position = $position ?? 'top';
@endphp
<div class="{{ $position === 'top' ? $this->getHeaderButtonsWrapperClasses() : $this->getFooterButtonsWrapperClasses() }}">
    <div class="lfb-buttons @if($this->isMultiStepForm() && $this->activeStepNumber() > 1)lfb-multi-step-buttons @endif">
        @if ($this->isMultiStepForm())
            @if ($this->activeStepNumber() > 1)
                <button wire:click="previousStep" class="{{$this->getPreviousButtonClasses()}} lfb-prev-multi-step-button ">
                    @if(method_exists($this, 'getStepPreviousIcon') && $this->getStepPreviousIcon())
                        {!! $this->getStepPreviousIcon() !!}
                    @endif
                    <span class="lbf-button-label">
                        {{ method_exists($this, 'getStepPreviousLabel') && $this->getStepPreviousLabel() ? $this->getStepPreviousLabel() : __('Previous Step') }}
                    </span>
                </button>
            @endif
            @if ($this->activeStepNumber() != $this->totalSteps())
                <button wire:click="nextStep" class="{{$this->getNextButtonClasses()}} lfb-next-multi-step-button">
                    <span class="lbf-button-label">
                        {{ method_exists($this, 'getStepNextLabel') && $this->getStepNextLabel() ? $this->getStepNextLabel() : __('Next Step') }}
                    </span>
                    @if(method_exists($this, 'getStepNextIcon') && $this->getStepNextIcon())
                        {!! $this->getStepNextIcon() !!}
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
