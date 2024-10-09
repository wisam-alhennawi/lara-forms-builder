<div class="{{ $fieldWrapperClass }}">
    @include('lara-forms-builder::includes.field-label')
    @php
        $fieldValue = $model->$key->value ?? $model->$key;
        if ($isGrouped) {
            $optionKeyGroupLabel = '';
            foreach ($selectOptions as $groupLabel => $options) {
                $optionKey = array_search($fieldValue, array_column($options, 'value'));
                if (!is_numeric($optionKey) && $optionKey == false) {
                    $optionKey = array_search($this->formProperties[$key], array_column($options, 'value'));
                }
                if (is_numeric($optionKey)) {
                    $optionKeyGroupLabel = $groupLabel;
                    break;
                }
            }
        } else {
            $optionKey = array_search($fieldValue, array_column($selectOptions, 'value'));
            if (!is_numeric($optionKey) && $optionKey == false) {
                $optionKey = array_search($this->formProperties[$key], array_column($selectOptions, 'value'));
            }
        }
    @endphp
    @if ((isset($mode) && ($mode == 'view' || $mode == 'confirm')) || isset($readOnly) && $readOnly)
        <div class="lfb-input-wrapper">
            <input id="{{ $key }}"
                   name="{{ $key }}"
                   type="text"
                   value=@if($isGrouped && $optionKeyGroupLabel)
                            "{{ $selectOptions[$optionKeyGroupLabel][$optionKey]['label'] }}"
                          @elseif(is_numeric($optionKey) && array_key_exists($optionKey, $selectOptions))
                            "{{ $selectOptions[$optionKey]['label'] }}"
                          @else
                            "-"
                          @endif
                   class="lfb-input lfb-disabled"
                   readonly
                   disabled
            >
        </div>
    @else
        <div class="lfb-input-wrapper">
            @if($styled)
            @include('lara-forms-builder::includes.select-script')
            <div x-data="{
                             ...lfbStyledSelect({{ json_encode($selectOptions) }}),
                             value: @entangle('formProperties.'.$key).live
                         }"
                 wire:key="form-select-component-{{ md5($key) }}"
                 id="formProperties-{{ $key }}"
                 name="formProperties.{{ $key }}"
                 class="relative lfb-input"
            >
                <div
                    class="lfb-styled-select-input"
                    :class="show ? 'lfb-styled-select-input-expanded': 'lfb-styled-select-input-collapsed'"
                >
                    <div class="flex flex-grow py-2 px-3" x-on:click="open()">
                        <div x-text="selectedLabel">&nbsp;</div>
                        <div x-show="!value" x-text="placeholder" class="text-gray-400">&nbsp;</div>
                    </div>
                    <div class="flex">
                        <div x-on:click="toggle()" class="pt-2.5 px-3">
                            <div x-show="!show && !value" class="w-4 h-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                            <div x-cloak x-show="show && !value" class="w-4 h-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                            </div>
                        </div>
                        <button x-cloak x-show="value" x-on:click="removeSelected()" class="px-3">
                            <!-- heroicon:x-mark -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div x-cloak x-show="show" x-on:click.outside="close()" class="absolute z-10 lfb-styled-select-options">
                    @if ($searchable)
                    <div class="lfb-styled-select-search-container">
                        <div class="lfb-styled-select-search-icon-wrapper">
                            <div class="lfb-styled-select-search-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                        </div>
                        <input
                            type="text"
                            x-model="search"
                            class="lfb-styled-select-search-input"
                            placeholder="{{ __('Search') }}"
                        >
                        <button class="lfb-styled-select-search-button" x-on:click="resetSearch()">
                            <div x-show="search.length > 0"  class="w-4 h-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </button>
                    </div>
                    @endif
                    <ul>
                        <template x-for="item in filteredOptions" :key="item.value">
                            <li x-on:click="selectOption(item)"
                                x-text="item.label">
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            @else
            <select wire:model.live="formProperties.{{ $key }}"
                    id="formProperties-{{ $key }}"
                    name="formProperties.{{ $key }}"
                    class="lfb-input"
            >
                <option value>{{ __('Please select...') }}</option>
                @if($isGrouped)
                    @foreach($selectOptions as $groupLabel => $options)
                        <optgroup label="{{ $groupLabel }}">
                            @foreach($options as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @else
                    @foreach($selectOptions as $option)
                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                    @endforeach
                @endif
            </select>
            @endif
        </div>
        @include('lara-forms-builder::includes.field-error-message')
        @include('lara-forms-builder::includes.field-help-text')
    @endif
</div>
