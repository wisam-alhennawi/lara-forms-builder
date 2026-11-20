<x-confirmation-modal wire:model="showConfirmationModal" :icon="$icon" :icon-class="$iconClass" :icon-wrapper-class="$iconWrapperClass">
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="content">
        {!! $content !!}
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="hideConfirmationModal" wire:loading.attr="disabled">
            {!! __('Cancel') !!}
        </x-secondary-button>

        @if ($isDanger)
        <x-danger-button class="ml-2" wire:click="confirm" wire:loading.attr="disabled">
            {!! __('Confirm') !!}
        </x-danger-button>
        @else
        <x-button class="ml-2 {{ $confirmButtonClass }}" wire:click="confirm" wire:loading.attr="disabled">
            {!! __('Confirm') !!}
        </x-button>
        @endif
    </x-slot>
</x-confirmation-modal>
