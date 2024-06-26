@once
    @script
    <script>
        window.pikadayTranslations =  {
            previousMonth : '{{ __('Previous Month') }}',
            nextMonth     : '{{ __('Next Month') }}',
            months        : ['{{ __('January') }}','{{ __('February') }}','{{ __('March') }}','{{ __('April') }}','{{ __('May') }}','{{ __('June') }}','{{ __('July') }}','{{ __('August') }}','{{ __('September') }}','{{ __('October') }}','{{ __('November') }}','{{ __('December') }}'],
            weekdays      : ['{{ __('Sunday') }}','{{ __('Monday') }}','{{ __('Tuesday') }}','{{ __('Wednesday') }}','{{ __('Thursday') }}','{{ __('Friday') }}','{{ __('Saturday') }}'],
            weekdaysShort : ['{{ __('Sun') }}','{{ __('Mon') }}','{{ __('Tue') }}','{{ __('Wed') }}','{{ __('Thu') }}','{{ __('Fri') }}','{{ __('Sat') }}']
        };
    </script>
    @endscript
@endonce

<div class="{{ $fieldWrapperClass }} @error('formProperties.' .  $key){{$defaultFieldErrorWrapperClasses}}@enderror">
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            <input x-ref="field"
                   id="formProperties-{{ $key }}"
                   name="formProperties.{{ $key }}"
                   type="text"
                   value="{{ $this->formProperties[$key] }}"
                   class="lfb-input lfb-disabled"
                   readonly
                   disabled
            >
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @else
        <div class="lfb-input-wrapper">
            <div x-data="{ value: @entangle('formProperties.'.$key).live }"
                 x-init="new Pikaday({
                                         field: $refs.input,
                                         format: 'DD.MM.YYYY',
                                         firstDay: 1,
                                         showWeekNumber: true,
                                         i18n: pikadayTranslations,
                                         theme: 'pikaday-white'
                                      });"
                 x-on:change="value = $event.target.value"
                 wire:key="form-date-picker-component-{{ md5($key) }}"
                 id="form-date-picker-component-{{ $key }}"
                 name="form-date-picker-component-{{ $key }}"
            >
                <input x-ref="input"
                       x-bind:value="value"
                       type="text"
                       @class([
                           'lfb-input',
                           'lfb-readonly' => $readOnly
                       ])
                       @readonly($readOnly)
                >
            </div>
            @include('lara-forms-builder::includes.field-error-message')
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @endif
    @include('lara-forms-builder::includes.field-help-text')
</div>
