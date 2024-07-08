<div class="{{ $fieldWrapperClass }} @error('formProperties.' .  $key){{ $fieldErrorWrapperClass }}@enderror">
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            <textarea wire:model.live="formProperties.{{ $key }}"
                      id="formProperties-{{ $key }}"
                      name="formProperties.{{ $key }}"
                      rows="{{$rows}}"
                      class="lfb-textarea lfb-disabled"
                      readonly
                      disabled
            >
            </textarea>
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @else
        <div class="lfb-input-wrapper">
            <textarea wire:key="form-textarea-component-{{ md5($key) }}"
                      wire:model.live="formProperties.{{ $key }}"
                      id="formProperties-{{ $key }}"
                      name="formProperties.{{ $key }}"
                      rows="{{$rows}}"
                      @class([
                          'lfb-textarea',
                          'lfb-readonly' => $readOnly
                      ])
                      @readonly($readOnly)
            >
            </textarea>
            @include('lara-forms-builder::includes.field-error-message')
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @endif
    @include('lara-forms-builder::includes.field-help-text')
</div>
