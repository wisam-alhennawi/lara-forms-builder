@php
    $groupWrapperClass = $this->getDefaultGroupWrapperClass();
    $defaultFieldWrapperClass = $this->getDefaultFieldWrapperClass();
    $defaultCardFieldErrorWrapperClasses = $this->getDefaultCardFieldErrorWrapperClasses();
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
        <div class="rounded-md bg-red-50 p-4 mb-4">
            <h3 class="text-sm font-medium text-red-800">{{ __('Error on submit') }}</h3>
            <div class="mt-2 text-sm text-red-700">
                <p>{{ $message }}</p>
            </div>
        </div>
    @enderror

    @if (!isset($hasTabs) || (isset($hasTabs) && !$hasTabs))
        @include('lara-forms-builder::includes.buttons')
    @endif
</div>
