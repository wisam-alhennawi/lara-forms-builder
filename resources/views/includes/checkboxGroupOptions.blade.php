@php
    $hasExtraElement = $enableExtraElements && ($option['has_extra_element'] ?? false);
    $selectedValues = $this->formProperties[$key] ?? [];
    $isSelected = is_array($selectedValues) && in_array($option['value'], $selectedValues, true);
    $isDisabled = (isset($mode) && ($mode == 'view' || $mode == 'confirm')) || (isset($option['disabled']) && $option['disabled']) || $readOnly;
    $extraElementDisabled = $isDisabled || ! $isSelected;
@endphp
<div class="lfb-checkbox-group-item @if($hasExtraElement)lfb-checkbox-group-item-with-extra @endif">
       <div class="lfb-checkbox-group-item-content">
              <input wire:key="form-checkbox-group-component-{{ md5($key) }}"
                     wire:model.live="formProperties.{{ $key }}"
                     id="{{ $key }}.{{ $option['value'] }}"
                     type="checkbox"
                     value="{{ $option['value'] }}"
                     @class([
                     'lfb-checkbox',
                     'lfb-disabled' => $isDisabled,
                     ])
                     @disabled($isDisabled)
              >
              <label class="lfb-label lfb-label-spacing print:text-xs" for="{{ $key }}.{{ $option['value'] }}">
                     {!! $option['label'] !!}
              </label>
       </div>
       @if($hasExtraElement)
              <div class="lfb-input-wrapper lfb-checkbox-group-extra">
                     <input wire:key="form-checkbox-group-extra-{{ md5($key . '-' . $option['value']) }}"
                            wire:model.live="formProperties.{{ $key }}_extra.{{ $option['value'] }}"
                            id="{{ $key }}.{{ $option['value'] }}-extra"
                            name="formProperties.{{ $key }}_extra.{{ $option['value'] }}"
                            type="text"
                            @class([
                                'lfb-input',
                                'lfb-readonly' => $extraElementDisabled,
                            ])
                            @readonly($extraElementDisabled)
                            @disabled($extraElementDisabled)
                     >
                     @php
                            $extraKey = "{$key}_extra.{$option['value']}";
                     @endphp
                     @include('lara-forms-builder::includes.field-error-message', ['key' => $extraKey])
                     @include('lara-forms-builder::includes.field-form-warning', ['key' => $extraKey])
              </div>
       @endif
</div>
