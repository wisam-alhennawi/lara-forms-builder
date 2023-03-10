@once
    <script>
        window.pikadayTranslations =  {
            previousMonth : '{{ __('Previous Month') }}',
            nextMonth     : '{{ __('Next Month') }}',
            months        : ['{{ __('January') }}','{{ __('February') }}','{{ __('March') }}','{{ __('April') }}','{{ __('May') }}','{{ __('June') }}','{{ __('July') }}','{{ __('August') }}','{{ __('September') }}','{{ __('October') }}','{{ __('November') }}','{{ __('December') }}'],
            weekdays      : ['{{ __('Sunday') }}','{{ __('Monday') }}','{{ __('Tuesday') }}','{{ __('Wednesday') }}','{{ __('Thursday') }}','{{ __('Friday') }}','{{ __('Saturday') }}'],
            weekdaysShort : ['{{ __('Sun') }}','{{ __('Mon') }}','{{ __('Tue') }}','{{ __('Wed') }}','{{ __('Thu') }}','{{ __('Fri') }}','{{ __('Sat') }}']
        };
    </script>
@endonce

<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="block text-sm font-medium text-gray-800">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required|'))
        *
        @endif
    </label>
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="relative mt-1">
            <input type="text" name="{{ $key }}" id="{{ $key }}" x-ref="field"
            value="{{ $this->$key }}"
            class="mt-1 block w-full rounded border text-gray-600 border-gray-200 focus:border-gray-300 focus:ring-gray-300 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none disabled:pointer-events-none" readonly disabled>
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="mt-2 text-sm text-yellow-600">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @else
        <div class="mt-1">
            <div
                wire:key="form-date-picker-component-{{ md5($key) }}"
                name="form-date-picker-component-{{ $key }}"
                id="form-date-picker-component-{{ $key }}"
                x-data="{ value: @entangle($key) }"
                x-on:change="value = $event.target.value"
                x-init="new Pikaday({ field: $refs.input, format: 'DD.MM.YYYY', firstDay: 1, showWeekNumber: true, i18n: pikadayTranslations, theme: 'pikaday-white' });"
            >
                <input
                    x-ref="input"
                    x-bind:value="value"
                    type="text"
                    class="mt-1 block w-full rounded border text-gray-600 border-gray-200 focus:border-gray-300 focus:ring-gray-300 @if(isset($readOnly) && $readOnly) read-only:bg-gray-100 read-only:pointer-events-none read-only:select-none @endif" >
            </div>
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
