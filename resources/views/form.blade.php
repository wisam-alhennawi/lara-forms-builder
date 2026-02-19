@php
    $groupWrapperClass = $this->getDefaultGroupWrapperClass();
    $defaultFieldWrapperClass = $this->getDefaultFieldWrapperClass();
    $defaultCardFieldErrorWrapperClasses = $this->getDefaultCardFieldErrorWrapperClasses();
    $defaultFieldErrorWrapperClasses = $this->getDefaultFieldErrorWrapperClasses();
@endphp

<div id="lara-forms-builder">
    @if ($headView)
        @include($headView)
    @endif
    @if (isset($hasTabs) && $hasTabs)
        @include('lara-forms-builder::includes.tabs')
    @else
        @include('lara-forms-builder::includes.fields')
    @endif
    @error('formSubmit')
        <div class="lfb-error-wrapper">
            <h3 class="lfb-error-title">{{ __('Error on submit') }}</h3>
            <div class="lfb-error-message">
                <p>{{ $message }}</p>
            </div>
        </div>
    @enderror
    @if (!isset($hasTabs) || (isset($hasTabs) && !$hasTabs))
        @include('lara-forms-builder::includes.buttons')
    @endif
    @if($scrollToFirstError)
       @include('lara-forms-builder::includes.scroll-to-first-error-script')
    @endif 
</div>
