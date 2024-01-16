@php
    $selectedItem = $key . '_search_picker_selected_value';
@endphp

<div x-data="{
                showResults: true,
             }"
     @click.outside="showResults = false"
     class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif"
>
    <label for="{{ $key }}" class="lfb-label">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required|'))
            <sup>*</sup>
        @endif
    </label>
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            <input
                type="text"
                name="{{ $key }}"
                id="{{ $key }}"
                value="@if ($this->{$selectedItem}){{ $this->{$selectedItem} }}@else - @endif"
                class="lfb-input lfb-disabled" readonly disabled
            >
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @else
        <div class="lfb-input-wrapper">
            @if($this->{$selectedItem})
                <div class="p-2 rounded border w-full flex items-center">
                        <span class="w-full">{{ $this->{$selectedItem} }}</span>
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
                <input
                    wire:key="form-input-component-{{ md5($key) }}"
                    type="text"
                    placeholder="{{ $placeholder }}"
                    name="{{ $key }}"
                    id="{{ $key }}"
                    class="lfb-input @if(isset($readOnly) && $readOnly) lfb-readonly @endif"
                    wire:model="{{ $key }}"
                    @if(isset($readOnly) && $readOnly) readonly @endif
                    @keyup="showResults = true"
                >
                @error($key) <span class="lfb-alert lfb-alert-error">{{ $message }}</span> @enderror
                @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                    <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
                @endif
            @endif
        </div>

        @if($this->{$searchPickerResultsProperty})
            <div
                x-show="showResults"
                class="mt-1 w-full rounded border-2 border-secondary overflow-y-auto max-h-36"
            >
                @foreach($this->{$searchPickerResultsProperty} as $result)
                    <div
                        wire:click="setSearchPickerValue('{{ $result['key'] }}', '{{ $key }}')"
                        @click="showResults = false"
                        class="p-3 hover:bg-primary hover:text-white cursor-pointer text-sm flex justify-between items-center"
                    >
                        <span>{!! $result['value'] !!}</span>
                        @if (isset($result['labels']))
                            <div>
                                @foreach($result['labels'] as $label)
                                    <span class="py-0.5 rounded border px-2 text-sm border-gray-200 bg-gray-100 text-gray-500">{{ $label }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    @endif
    @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
    @endif
</div>
