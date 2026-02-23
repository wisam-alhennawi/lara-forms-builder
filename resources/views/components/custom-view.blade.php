<div class="{{ $fieldWrapperClass }}">
    @if(!empty($field['view']))
        @include($field['view'], [
            'key' => $key ?? null,
            'field' => $field,
            'formProperties' => $this->formProperties ?? [],
            'mode' => $mode ?? null,
            'model' => $model ?? null,
        ])
    @elseif(!empty($field['livewire_component']))  
        @livewire($field['livewire_component'], [
            'key' => $key ?? null,
            'field' => $field,
            'formProperties' => $this->formProperties ?? [],
            'mode' => $mode ?? null,
            'model' => $model ?? null,
        ], key('custom-livewire-component-' . $key))  
    @endif
</div>
