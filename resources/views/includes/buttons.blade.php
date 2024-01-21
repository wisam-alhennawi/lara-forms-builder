<div class="{{ $this->getFooterButtonsWrapperClasses() }}">
    <div class="lfb-buttons">
        <button wire:click="cancelOrBack" class="{{$this->getSecodaryButtonClasses()}}">
            {{ (isset($mode) && $mode == 'confirm') ? __('Back') : $cancelButtonLabel }}
        </button>
        @if (isset($mode) && $mode == 'view')
            @can('update', $model)
            <button wire:click="switchToEditMode" class="{{$this->getPrimaryButtonClasses()}}">
                {{ __('Edit') }}
            </button>
            @endcan
        @else
        <button wire:click="checkAndSave" type="button" class="{{$this->getPrimaryButtonClasses()}}" @if($disableSaveButton) disabled @endif wire:loading.attr="disabled">
            {{ (isset($mode) && $mode == 'confirm') ? __('Confirm') : $submitButtonLabel }}
        </button>
        @endif
    </div>
</div>
