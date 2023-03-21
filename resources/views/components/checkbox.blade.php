<!-- TODO: add css to config file-->
<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <fieldset class="mt-4">
        <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
            <div class="flex items-center">
                <input wire:key="form-checkbox-component-{{ md5($key) }}" type="checkbox" id="{{ $key }}" name="{{ $key }}" wire:model="{{ $key }}" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary"
                @if (isset($mode) && ($mode == 'view' || $mode == 'confirm')) disabled @endif>
                <label class="ml-3 block text-sm font-medium text-gray-700 print:text-xs" for="{{ $key }}">{!! $label !!}
                @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
                *
                @endif
                </label>
            </div>
        </div>
        @error($key) <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
        @if(isset($helpText) && $helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
        @endif
    </fieldset>
</div>
