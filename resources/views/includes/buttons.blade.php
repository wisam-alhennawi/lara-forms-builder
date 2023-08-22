<div class="lfb-buttons-wrapper">
    <div class="lfb-buttons">
        <button wire:click="cancel" class="{{$this->getSecodaryButtonClasses()}}">
            {{ $cancelButtonLabel }}
        </button>
        @if (isset($mode) && $mode == 'view')
            @can('update', $model)
            <button wire:click="switchToEditMode" class="{{$this->getPrimaryButtonClasses()}}">
                {{ __('Edit') }}
            </button>
            @endcan
        @else
        <button wire:click="checkAndSave" type="button" class="{{$this->getPrimaryButtonClasses()}}" @if($disableSaveButton) disabled @endif wire:loading.attr="disabled">
            {{ $submitButtonLabel }}
        </button>
        @endif
    </div>
</div>
