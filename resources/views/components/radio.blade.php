<div class="@if (isset($fieldWrapperClass)) {{$fieldWrapperClass}} @endif">
    <label for="{{ $key }}" class="lfb-label">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
            <sup>*</sup>
        @endif
    </label>
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        @if ($model->$key && array_key_exists(is_object($model->$key) && enum_exists($model->$key::class) ? $model->$key->value : $model->$key, $radioOptions))
            @php
                $fieldValue = $radioOptions[is_object($model->$key) && enum_exists($model->$key::class) ? $model->$key->value : $model->$key];
            @endphp
        @else
            @php
                $fieldValue = '-';
            @endphp
        @endif
        <div class="lfb-input-wrapper">
            <input type="text" name="{{ $key }}" id="{{ $key }}" value="{{$fieldValue}}" class="lfb-input lfb-disabled" readonly disabled>
        </div>
    @else
    <fieldset class="lfb-fieldset">
        <div class="lfb-fieldset-container">
            @foreach($radioOptions as $optionKey => $optionLabel)
            <div class="lfb-fieldset-item {{ array_key_first($radioOptions) !== $optionKey ? 'lfb-fieldset-item-spacing' : '' }}">
                <input wire:key="form-radio-component-{{ md5($key) }}" id="{{ $key . $loop->index }}" name="{{ $key }}" type="radio" value="{{ $optionKey }}" wire:model="{{ $key }}" class="lfb-radio">
                <label class="lfb-label lfb-label-spacing">{{ $optionLabel }}</label>
            </div>
            @endforeach
        </div>
        @error($key) <span class="lfb-alert lfb-alert-error">{{ $message }}</span> @enderror
        @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
        @endif
    </fieldset>
    @endif
</div>
