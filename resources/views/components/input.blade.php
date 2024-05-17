<div class="{{ $fieldWrapperClass }}">
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly" x-data="{ showSecretValue: false }">
            <input id="formProperties-{{ $key }}"
                   name="formProperties.{{ $key }}"
                   type="{{ $inputType ?? 'text' }}"
                   value="@if ($model->$key || is_numeric($model->$key) || $this->formProperties[$key] || is_numeric($this->formProperties[$key])){{ $model->$key ?? $this->formProperties[$key] }}@else - @endif"
                   x-ref="field"
                   class="lfb-input lfb-disabled"
                   readonly
                   disabled
            >
            @if(isset($inputType) && $inputType == 'password' && $secretValueToggle)
                @include('lara-forms-builder::includes.secret-value-toggle')
            @endif
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @else
        <div @class([
            'lfb-input-wrapper',
            'relative' => isset($secretValueToggle) && $secretValueToggle
            ])
            x-data="{ showSecretValue: false }">
            <input wire:key="form-input-component-{{ md5($key) }}"
                @if(isset($typingAttributes['type']) && isset($typingAttributes['value']))
                    wire:model.live.{{ $typingAttributes['type'] }}.{{ $typingAttributes['value'] }}ms="formProperties.{{ $key }}"
                @else
                    wire:model.live="formProperties.{{ $key }}"
                @endif
                   id="formProperties-{{ $key }}"
                   name="formProperties.{{ $key }}"
                   type="{{ $inputType ?? 'text' }}"
                   x-ref="field"
                   @class([
                       'lfb-input',
                       'lfb-readonly' => $readOnly
                   ])
                   @readonly($readOnly)
            >
            @if(isset($inputType) && $inputType == 'password' && isset($secretValueToggle) && $secretValueToggle)
                @include('lara-forms-builder::includes.secret-value-toggle')
            @endif
            @include('lara-forms-builder::includes.field-error-message')
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @endif
    @include('lara-forms-builder::includes.field-help-text')
</div>
