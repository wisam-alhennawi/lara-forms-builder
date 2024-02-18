<button wire:click="checkAndSave" type="button" class="{{$this->getPrimaryButtonClasses()}}" @if($disableSaveButton) disabled @endif wire:loading.attr="disabled">
    {{ (isset($mode) && $mode == 'confirm') ? __('Confirm') : $submitButtonLabel }}
</button>