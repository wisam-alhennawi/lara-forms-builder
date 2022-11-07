<div class="mb-5">
    <fieldset class="mt-4">
        <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
            <div class="flex items-center">
                <input name="{{ $key }}" type="checkbox" wire:model="{{ $key }}" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary"
                @if (isset($mode) && $mode == 'view') disabled @endif>
                <label class="ml-3 block text-sm font-medium text-gray-700">{{ $label }}
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
