<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            <input type="@if(isset($inputType)){{$inputType}}@else{{'text'}}@endif" name="{{ $key }}" id="{{ $key }}"
            value="@if ($model->$key || is_numeric($model->$key) || $this->formProperties[$key] || is_numeric($this->formProperties[$key])){{ $model->$key ?? $this->formProperties[$key] }}@else - @endif"
            class="lfb-input lfb-disabled" readonly disabled>
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @else
        <div class="lfb-input-wrapper">
            <input wire:key="form-input-component-{{ md5($key) }}" type="@if(isset($inputType)){{$inputType}}@else{{'text'}}@endif" name="{{ $key }}" id="{{ $key }}" class="lfb-input @if(isset($readOnly) && $readOnly) lfb-readonly @endif"
                wire:model.live="formProperties.{{ $key }}" @if(isset($readOnly) && $readOnly) readonly @endif>
            @include('lara-forms-builder::includes.field-error-message')
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @endif
    @include('lara-forms-builder::includes.field-help-text')
</div>
