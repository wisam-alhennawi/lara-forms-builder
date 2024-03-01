@php
    $selectedItem = $key . '_search_picker_selected_value';
@endphp
<div x-data="{ showResults: true, }"
     @click.outside="showResults = false"
     class="{{ $fieldWrapperClass }}"
>
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            <input id="formProperties-{{ $key }}"
                   name="formProperties.{{ $key }}"
                   type="text"
                   value="@if ($this->{$selectedItem}){{ $this->{$selectedItem} }}@else - @endif"
                   class="lfb-input lfb-disabled"
                   readonly
                   disabled
            >
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @else
        <div class="lfb-input-wrapper">
            @if($this->{$selectedItem})
                <div class="lfb-search-picker-selected-item">
                    <span>{{ $this->{$selectedItem} }}</span>
                    <button wire:click.prevent="setSearchPickerValue(null, '{{ $key }}')">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd">
                            </path>
                        </svg>
                    </button>
                </div>
            @else
                <input wire:key="form-input-component-{{ md5($key) }}"
                       wire:model.live="formProperties.{{ $key }}_search_picker"
                       @keyup="showResults = true"
                       id="formProperties-{{ $key }}"
                       name="formProperties.{{ $key }}"
                       type="text"
                       placeholder="{{ $placeholder }}"
                       @class([
                           'lfb-input',
                           'lfb-readonly' => $readOnly
                       ])
                       @readonly($readOnly)
                >
                @include('lara-forms-builder::includes.field-error-message')
                @include('lara-forms-builder::includes.field-form-warning')
            @endif
        </div>
        @if($this->{$searchPickerResultsProperty})
            <div class="relative">
                <div x-show="showResults"
                     class="lfb-search-picker-results-container"
                >
                    @foreach($this->{$searchPickerResultsProperty} as $result)
                        <div wire:click="setSearchPickerValue('{{ $result['key'] }}', '{{ $key }}')"
                             @click="showResults = false"
                             class="lfb-search-picker-result"
                        >
                            <span>{!! $result['value'] !!}</span>
                            @if (isset($result['labels']))
                                <div class="lfb-search-picker-result-labels-container">
                                    @foreach($result['labels'] as $label)
                                        <span class="lfb-search-picker-result-label">{{ $label }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
    @include('lara-forms-builder::includes.field-help-text')
</div>
