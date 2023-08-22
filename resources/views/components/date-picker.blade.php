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
    <label for="{{ $key }}" class="lfb-label">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
            <sup>*</sup>
        @endif
    </label>
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            <input type="text" name="{{ $key }}" id="{{ $key }}" x-ref="field"
            value="{{ $this->$key }}"
            class="lfb-input lfb-disabled" readonly disabled>
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @else
        <div class="lfb-input-wrapper">
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
                    @if(isset($readOnly) && $readOnly) readonly @endif
                    class="lfb-input @if(isset($readOnly) && $readOnly) lfb-readonly @endif" >
            </div>
            @error($key) <span class="lfb-alert lfb-alert-error">{{ $message }}</span> @enderror
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @endif
    @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
    @endif
</div>
