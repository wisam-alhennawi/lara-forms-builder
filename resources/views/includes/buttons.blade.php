<div class="border-t border-gray-200 mt-8 py-6">
    <div class="px-4 flex justify-end gap-2">
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
        <button wire:click="checkAndSave" type="button" class="{{$this->getPrimaryButtonClasses()}}">
            {{ $submitButtonLabel }}
        </button>
        @endif
    </div>
</div>