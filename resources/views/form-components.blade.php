
@switch($field['type'])
    @case('input')
        @include('lara-forms-builder::components.input', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'inputType' => isset($field['inputType']) ? $field['inputType'] : 'text',
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass
        ])
        @break
    @case('select')
        @include('lara-forms-builder::components.select', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'selectOptions' => $field['options'],
            'isGrouped' => isset($field['isGrouped']) ? $field['isGrouped'] : false,
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'styled' => isset($field['styled']) ? $field['styled'] : false,
            'searchable' => isset($field['searchable']) ? $field['searchable'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass
        ])
        @break
    @case('radio')
        @include('lara-forms-builder::components.radio', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'radioOptions' => $field['options'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass
        ])
        @break
    @case('checkbox')
        @include('lara-forms-builder::components.checkbox', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass
        ])
        @break
    @case('textarea')
        @include('lara-forms-builder::components.textarea', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass,
            'rows' => isset($field['rows']) ? $field['rows'] : 5
        ])
        @break
    @case('date-picker')
        @include('lara-forms-builder::components.date-picker', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass
        ])
        @break
    @case('cards')
        @include('lara-forms-builder::components.cards', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'selectOptions' => $field['options'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass,
            'cardFieldErrorWrapperClass' => isset($field['card_field_error_wrapper_class']) ? $field['card_field_error_wrapper_class'] : $defaultCardFieldErrorWrapperClasses,
            'icon' => isset($field['icon']) ? $field['icon'] : null,
            'errorMessageIcon' => isset($field['errorMessageIcon']) ? $field['errorMessageIcon'] : null,
        ])
        @break
    @case('checkbox-group')
        @include('lara-forms-builder::components.checkbox-group', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'checkboxGroupOptions' => $field['options'],
            'hasCategory' => isset($field['hasCategory']) ? $field['hasCategory'] : false,
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass,
        ])
        @break
    @case('file')
        @include('lara-forms-builder::components.file-upload', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass,
            'removeIcon' => isset($field['removeIcon']) ? $field['removeIcon'] : null,
            'preview' => isset($field['preview']) ? $field['preview'] : null,
        ])
        @break
    @case('search-picker')
        @include('lara-forms-builder::components.search-picker', [
            'key' => $fieldKey,
            'label' => $field['label'],
            'searchPickerResultsProperty' => $field['searchPickerResultsProperty'],
            'placeholder' => isset($field['placeholder']) ? $field['placeholder'] : '',
            'helpText' => isset($field['helpText']) ? $field['helpText'] : '',
            'readOnly' => isset($field['readOnly']) ? $field['readOnly'] : false,
            'fieldWrapperClass' => isset($field['field_wrapper_class']) ? $field['field_wrapper_class'] : $defaultFieldWrapperClass
        ])
        @break
    @default
@endswitch
