<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="lfb-label">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required|'))
            <sup>*</sup>
        @endif
    </label>
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            <textarea name="form-textarea-component-{{ $key }}" id="form-textarea-component-{{ $key }}"
                wire:model="{{ $key }}"
                rows="{{$rows}}"
                class="lfb-textarea lfb-disabled" readonly disabled>
            </textarea>
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @else
        <div class="lfb-input-wrapper">
            <textarea wire:key="form-textarea-component-{{ md5($key) }}" name="{{ $key }}" id="form-textarea-component-{{ $key }}"
                class="lfb-textarea @if(isset($readOnly) && $readOnly) lfb-readonly @endif"
                wire:model="{{ $key }}"
                rows="{{$rows}}"
                @if(isset($readOnly) && $readOnly) readonly @endif>
            </textarea>
            @error($key) <span class="lfb-alert lfb-alert-error">{{ $message }}</span> @enderror
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @endif
    @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
    @endif
</div>
