<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view'))  and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
        *
        @endif
    </label>
    @php
        $fieldValue = $model->$key->value ?? $model->$key;
        $optionKey = array_search($fieldValue, array_column($selectOptions, 'value'));
        if (!is_numeric($optionKey) && $optionKey == false) {
            $optionKey = array_search($this->$key, array_column($selectOptions, 'value'));
        }
    @endphp
    @if ((isset($mode) && ($mode == 'view' || $mode == 'confirm')) || isset($readOnly) && $readOnly)
        <div class="mt-1">
            <input type="text" name="{{ $key }}" id="{{ $key }}" value="@if(is_numeric($optionKey) && array_key_exists($optionKey, $selectOptions)){{ $selectOptions[$optionKey]['label'] }}@else - @endif" class="mt-1 block w-full rounded border text-gray-600 border-gray-200 focus:border-gray-300 focus:ring-gray-300 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none disabled:pointer-events-none" readonly disabled>
        </div>
    @else
        <select id="{{ $key }}" name="{{ $key }}" class="mt-1 block w-full rounded border text-gray-600 border-gray-200 focus:border-gray-300 focus:ring-gray-300"
            wire:model="{{ $key }}">
            <option value>{{ __('Please select...') }}</option>
            @foreach($selectOptions as $option)
            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
            @endforeach
        </select>
        @error($key) <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
        @if(isset($helpText))
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
        @endif
    @endif
</div>
