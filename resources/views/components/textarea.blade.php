<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-800">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required|'))
        *
        @endif
    </label>
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="relative mt-1">
            <textarea name="form-textarea-component-{{ $key }}" id="form-textarea-component-{{ $key }}"
                wire:model="{{ $key }}"
                rows="{{$rows}}"
                class="mt-1 block w-full rounded border text-gray-600 border-gray-200 focus:border-gray-300 focus:ring-gray-300 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none disabled:pointer-events-none" readonly disabled>
            </textarea>
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="mt-2 text-sm text-yellow-600">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @else
        <div class="mt-1">
            <textarea wire:key="form-textarea-component-{{ md5($key) }}" name="{{ $key }}" id="form-textarea-component-{{ $key }}"
                class="mt-1 block w-full rounded border text-gray-600 border-gray-200 focus:border-gray-300 focus:ring-gray-300 @if(isset($readOnly) && $readOnly) read-only:bg-gray-100 read-only:pointer-events-none read-only:select-none @endif"
                wire:model="{{ $key }}"
                rows="{{$rows}}"
                @if(isset($readOnly) && $readOnly) readonly @endif>
            </textarea>
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
