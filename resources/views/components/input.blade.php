<div class="mb-5">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
        *
        @endif
    </label>
    @if (isset($mode) && $mode == 'view')
        <p class="mt-1 ml-2">
            @if ($model->$key)
                {{ $model->$key }}
            @else
                -
            @endif
        </p>
    @else
        <div class="mt-1">
            <input type="@if(isset($inputType)){{$inputType}}@else{{'text'}}@endif" name="{{ $key }}" id="{{ $key }}" class="block w-full rounded-md border-primary focus:outline-gray-100 focus:ring-primary focus:ring-0 focus:border-primary sm:text-sm"
                wire:model="{{ $key }}" @if(isset($readOnly) && $readOnly) readonly @endif>
            @error($key) <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
        </div>
        @if(isset($helpText) && $helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
        @endif
    @endif
</div>
