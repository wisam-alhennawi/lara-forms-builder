@php
    $componentData = $field['data'] ?? [];
    $componentData = is_array($componentData) ? $componentData : []
@endphp
<div class="{{ $fieldWrapperClass }}"
    @if(!empty($field['livewire_component'])) wire:ignore @endif>
    @if(!empty($field['view']))
        @include($field['view'], $componentData)
    @elseif(!empty($field['livewire_component']))
        @livewire($field['livewire_component'], $componentData)
    @endif
</div>

