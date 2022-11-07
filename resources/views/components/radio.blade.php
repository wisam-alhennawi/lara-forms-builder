<div class="mb-5">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
        *
        @endif
    </label>
    @if (isset($mode) && $mode == 'view')
        <p class="mt-1 ml-2">
            @if ($model->$key && array_key_exists(is_object($model->$key) && enum_exists($model->$key::class) ? $model->$key->value : $model->$key, $radioOptions))
                {{ $radioOptions[is_object($model->$key) && enum_exists($model->$key::class) ? $model->$key->value : $model->$key] }}
            @else
                -
            @endif
        </p>
    @else
    <fieldset class="mt-4">
        <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
            @foreach($radioOptions as $optionKey => $optionLabel)
            <div class="flex items-center">
                <input name="{{ $key }}" type="radio" value="{{ $optionKey }}" wire:model="{{ $key }}" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
                <label class="ml-3 block text-sm font-medium text-gray-700">{{ $optionLabel }}</label>
            </div>
            @endforeach
        </div>
        @error($key) <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
        @if(isset($helpText) && $helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
        @endif
    </fieldset>
    @endif
</div>
