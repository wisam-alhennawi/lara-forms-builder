<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="lfb-label">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
            <sup>*</sup>
        @endif
    </label>
    <div class="lfb-fieldset lfb-fieldset-container">
        <div class="lfb-checkbox-group">
            @foreach($checkboxGroupOptions as $option)
                <div class="lfb-checkbox-group-item">
                    <input wire:key="form-checkbox-group-component-{{ md5($key) }}" type="checkbox"
                           id="{{ $option['value'] }}" value="{{ $option['value'] }}"
                           wire:model="{{ $key }}" class="lfb-checkbox"
                    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm')) disabled @endif>
                        <label class="lfb-label lfb-label-spacing print:text-xs"
                               for="{{ $option['value'] }}">{{ $option['label'] }}
                        </label>
                </div>
            @endforeach
        </div>
    </div>
    @error($key) <span class="lfb-alert lfb-alert-error">{{ $message }}</span> @enderror
    @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
    @endif
</div>
