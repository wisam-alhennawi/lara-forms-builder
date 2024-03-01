<div class="{{ $fieldWrapperClass }}">
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            <input id="formProperties-{{ $key }}"
                   name="formProperties.{{ $key }}"
                   type="{{ $inputType ?? 'text' }}"
                   value="@if ($model->$key || is_numeric($model->$key) || $this->formProperties[$key] || is_numeric($this->formProperties[$key])){{ $model->$key ?? $this->formProperties[$key] }}@else - @endif"
                   class="lfb-input lfb-disabled"
                   readonly
                   disabled
            >
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @else
        <div class="lfb-input-wrapper">
            <input wire:key="form-input-component-{{ md5($key) }}"
                   wire:model.live="formProperties.{{ $key }}"
                   id="formProperties-{{ $key }}"
                   name="formProperties.{{ $key }}"
                   type="{{ $inputType ?? 'text' }}"
                   @class([
                       'lfb-input',
                       'lfb-readonly' => $readOnly
                   ])
                   @readonly($readOnly)
            >
            @include('lara-forms-builder::includes.field-error-message')
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @endif
    @include('lara-forms-builder::includes.field-help-text')
</div>
