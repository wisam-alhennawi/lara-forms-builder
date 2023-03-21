<!-- TODO: add css to config file-->
<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-800">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required|'))
        *
        @endif
    </label>
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="relative mt-1">
            <input type="@if(isset($inputType)){{$inputType}}@else{{'text'}}@endif" name="{{ $key }}" id="{{ $key }}" x-ref="field"
            value="@if ($model->$key || is_numeric($model->$key) || $this->$key || is_numeric($this->$key)){{ $model->$key ?? $this->$key }}@else - @endif"
            class="mt-1 block w-full rounded border text-gray-600 border-gray-200 focus:border-gray-300 focus:ring-gray-300 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none disabled:pointer-events-none" readonly disabled>
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="mt-2 text-sm text-yellow-600">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @else
        <div class="mt-1">
            <input wire:key="form-input-component-{{ md5($key) }}" type="@if(isset($inputType)){{$inputType}}@else{{'text'}}@endif" name="{{ $key }}" id="{{ $key }}" class="mt-1 block w-full rounded border text-gray-600 border-gray-200 focus:border-gray-300 focus:ring-gray-300 @if(isset($readOnly) && $readOnly) read-only:bg-gray-100 read-only:pointer-events-none read-only:select-none @endif"
                wire:model="{{ $key }}" @if(isset($readOnly) && $readOnly) readonly @endif>
            @error($key) <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="mt-2 text-sm text-yellow-600">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @endif
    @if(isset($helpText) && $helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
    @endif
</div>
