<div class="mb-5">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view'))  and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
        *
        @endif
    </label>
    @if (isset($mode) && $mode == 'view')
        <p class="mt-1 ml-2">
            @php
                $optionKey = array_search($key, array_column($selectOptions, 'value'));
            @endphp
            @if (isset($selectOptions[$optionKey]))
                {{ $selectOptions[$optionKey]['label'] }}
            @else
                -
            @endif
        </p>
    @else
        <select id="{{ $key }}" name="{{ $key }}" class="mt-1 block w-full rounded-md border-primary focus:outline-gray-100 focus:ring-primary focus:ring-0 focus:border-primary py-2 pl-3 pr-10 text-base sm:text-sm"
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
