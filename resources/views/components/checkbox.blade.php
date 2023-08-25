<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <fieldset class="lfb-fieldset">
        <div class="lfb-fieldset-container">
            <div class="lfb-fieldset-item">
                <input wire:key="form-checkbox-component-{{ md5($key) }}" type="checkbox" id="{{ $key }}" name="{{ $key }}" wire:model="{{ $key }}" class="lfb-checkbox"
                @if (isset($mode) && ($mode == 'view' || $mode == 'confirm')) disabled @endif>
                <label class="lfb-label lfb-label-spacing print:text-xs" for="{{ $key }}">{!! $label !!}
                @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
                    <sup>*</sup>
                @endif
                </label>
            </div>
        </div>
        @error($key) <span class="lfb-alert lfb-alter-error">{{ $message }}</span> @enderror
        @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
        @endif
    </fieldset>
</div>
