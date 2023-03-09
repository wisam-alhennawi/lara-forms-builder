<div class="@if (isset($fieldWrapperClass)) {{$fieldWrapperClass}} @endif">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
        *
        @endif
    </label>
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="block w-full rounded-md border-gray-300 border px-3 py-2 shadow-sm sm:text-sm bg-gray-100 select-none mt-1">
            @if ($model->$key && array_key_exists(is_object($model->$key) && enum_exists($model->$key::class) ? $model->$key->value : $model->$key, $radioOptions))
                {{ $radioOptions[is_object($model->$key) && enum_exists($model->$key::class) ? $model->$key->value : $model->$key] }}
            @else
                -
            @endif
        </div>
    @else
    <fieldset class="mt-4">
        <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
            @foreach($radioOptions as $optionKey => $optionLabel)
            <div class="flex items-center">
                <input wire:key="form-radion-component-{{ md5($key) }}" id="{{ $key . $loop->index }}" name="{{ $key }}" type="radio" value="{{ $optionKey }}" wire:model="{{ $key }}" class="h-4 w-4 border-gray-300 text-primary focus:ring-primary">
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
