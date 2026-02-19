<div class="{{ $fieldWrapperClass }} @error('formProperties.' .  $key){{ $fieldErrorWrapperClass }}@enderror">
    <fieldset class="lfb-fieldset">
        <div class="lfb-fieldset-container">
            <div class="lfb-fieldset-item">
                <input wire:key="form-checkbox-component-{{ md5($key) }}"
                    wire:model.live="formProperties.{{ $key }}"
                    id="formProperties-{{ $key }}"
                    name="formProperties.{{ $key }}"
                    type="checkbox"
                    @class([
                        'lfb-checkbox',
                        'lfb-readonly' => (isset($mode) && ($mode == 'view' || $mode == 'confirm')) || $readOnly,
                    ])
                    @readonly((isset($mode) && ($mode == 'view' || $mode == 'confirm')) || $readOnly)
                    @disabled((isset($mode) && ($mode == 'view' || $mode == 'confirm')) || $readOnly)
                >
                <label class="lfb-label lfb-label-spacing print:text-xs" for="formProperties-{{ $key }}">{!! $label !!}
                @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists('formProperties.' .  $key, $rules) && str_contains($rules['formProperties.' .  $key], 'required'))
                    <sup>*</sup>
                @endif
                </label>
            </div>
        </div>
        @include('lara-forms-builder::includes.field-error-message')
        @include('lara-forms-builder::includes.field-help-text')
    </fieldset>
</div>
