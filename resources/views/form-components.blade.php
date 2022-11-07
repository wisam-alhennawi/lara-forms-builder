
@switch($field['type'])
    @case('input')
        @include('lara-forms-builder::components.input', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'inputType' => isset($field['inputType']) ? $field['inputType'] : 'text',
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false
        ])
        @break
    @case('select')
        @include('lara-forms-builder::components.select', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'selectOptions' => $field['options'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : ''
        ])
        @break
    @case('radio')
        @include('lara-forms-builder::components.radio', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'radioOptions' => $field['options'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : ''
        ])
        @break
    @case('checkbox')
        @include('lara-forms-builder::components.checkbox', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : ''
        ])
        @break
    @default
@endswitch