@switch($field['type'])
    @case('input')
        @include('lara-forms-builder::components.input', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'inputType' => $field['inputType'] ?? 'text',
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'tooltip' => $field['tooltip'] ?? null,
            'secretValueToggle' => $field['secretValueToggle'] ?? false,
            'typingAttributes' =>  $field['typingAttributes'] ?? null,
        ])
        @break
    @case('select')
        @include('lara-forms-builder::components.select', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'selectOptions' => $field['options'],
            'isGrouped' => $field['isGrouped'] ?? false,
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'styled' => $field['styled'] ?? false,
            'searchable' => $field['searchable'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'tooltip' => $field['tooltip'] ?? null,
        ])
        @break
    @case('radio')
        @include('lara-forms-builder::components.radio', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'radioOptions' => $field['options'],
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'tooltip' => $field['tooltip'] ?? null,
        ])
        @break
    @case('checkbox')
        @include('lara-forms-builder::components.checkbox', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'tooltip' => $field['tooltip'] ?? null,
        ])
        @break
    @case('textarea')
        @include('lara-forms-builder::components.textarea', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'rows' => $field['rows'] ?? 5,
            'tooltip' => $field['tooltip'] ?? null,
        ])
        @break
    @case('date-picker')
        @include('lara-forms-builder::components.date-picker', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'tooltip' => $field['tooltip'] ?? null,
        ])
        @break
    @case('cards')
        @include('lara-forms-builder::components.cards', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'selectOptions' => $field['options'],
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'cardFieldErrorWrapperClass' => $field['card_field_error_wrapper_class'] ?? $defaultCardFieldErrorWrapperClasses,
            'icon' => $field['icon'] ?? null,
            'errorMessageIcon' => $field['errorMessageIcon'] ?? null,
        ])
        @break
    @case('checkbox-group')
        @include('lara-forms-builder::components.checkbox-group', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'checkboxGroupOptions' => $field['options'],
            'hasCategory' => $field['hasCategory'] ?? false,
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'tooltip' => $field['tooltip'] ?? null,
        ])
        @break
    @case('file')
        @include('lara-forms-builder::components.file-upload', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'removeIcon' => $field['removeIcon'] ?? null,
            'preview' => $field['preview'] ?? null,
            'tooltip' => $field['tooltip'] ?? null,
        ])
        @break
    @case('search-picker')
        @include('lara-forms-builder::components.search-picker', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'searchPickerResultsProperty' => $field['searchPickerResultsProperty'],
            'placeholder' => $field['placeholder'] ?? '',
            'helpText' => $field['helpText'] ?? '',
            'readOnly' => $field['readOnly'] ?? false,
            'fieldWrapperClass' => $field['field_wrapper_class'] ?? $defaultFieldWrapperClass,
            'tooltip' => $field['tooltip'] ?? null,
        ])
        @break
    @default
@endswitch
