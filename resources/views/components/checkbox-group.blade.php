<!-- TODO: add css to config file-->
<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-800">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
            *
        @endif
    </label>
    <div class="mt-4 space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
        <div class="flex flex-wrap">
            @foreach($checkboxGroupOptions as $option)
                <div class="flex mt-1 basis-1/4">
                    <input wire:key="form-checkbox-group-component-{{ md5($key) }}" type="checkbox"
                           id="{{ $option['value'] }}" value="{{ $option['value'] }}"
                           wire:model="{{ $key }}" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary"
                    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm')) disabled @endif>
                        <label class="ml-3 block text-sm font-medium text-gray-700 print:text-xs"
                               for="{{ $option['value'] }}">{{ $option['label'] }}
                        </label>
                </div>
            @endforeach
        </div>
    </div>
    @error($key) <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
    @if(isset($helpText) && $helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
    @endif
</div>
