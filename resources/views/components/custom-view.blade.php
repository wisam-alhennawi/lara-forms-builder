@php
    $componentData = $field['data'] ?? [];
    $componentData = is_array($componentData) ? $componentData : []
@endphp
<div class="{{ $fieldWrapperClass }}">
    @if(!empty($field['view']))
        @include($field['view'], $componentData)
    @elseif(!empty($field['livewire_component']))
        @livewire($field['livewire_component'], $componentData)
    @endif
</div>

