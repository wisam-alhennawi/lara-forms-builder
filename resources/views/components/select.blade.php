<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="lfb-label">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view'))  and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
            <sup>*</sup>
        @endif
    </label>
    @php
        $fieldValue = $model->$key->value ?? $model->$key;
        if ($isGrouped) {
            $optionKeyGroupLabel = '';
            foreach ($selectOptions as $groupLabel => $options) {
                $optionKey = array_search($fieldValue, array_column($options, 'value'));
                if (!is_numeric($optionKey) && $optionKey == false) {
                    $optionKey = array_search($this->$key, array_column($options, 'value'));
                }
                if (is_numeric($optionKey)) {
                    $optionKeyGroupLabel = $groupLabel;
                    break;
                }
            }
        } else {
            $optionKey = array_search($fieldValue, array_column($selectOptions, 'value'));
            if (!is_numeric($optionKey) && $optionKey == false) {
                $optionKey = array_search($this->$key, array_column($selectOptions, 'value'));
            }
        }
    @endphp
    @if ((isset($mode) && ($mode == 'view' || $mode == 'confirm')) || isset($readOnly) && $readOnly)
        <div class="lfb-input-wrapper">
            <input type="text" name="{{ $key }}" id="{{ $key }}"
                   value=@if($isGrouped && $optionKeyGroupLabel)
                              {{ $selectOptions[$optionKeyGroupLabel][$optionKey]['label'] }}
                          @elseif(is_numeric($optionKey) && array_key_exists($optionKey, $selectOptions))
                              {{ $selectOptions[$optionKey]['label'] }}
                          @else - @endif
                   class="lfb-input lfb-disabled" readonly disabled>
        </div>
    @else
        <div class="lfb-input-wrapper">
            <select id="{{ $key }}" name="{{ $key }}" class="lfb-input"
                    wire:model="{{ $key }}">
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
        </div>
        @error($key) <span class="lfb-alert lfb-alert-error">{{ $message }}</span> @enderror
        @if(isset($helpText))
            <p class="lfb-help-text">{{ $helpText }}</p>
        @endif
    @endif
</div>
