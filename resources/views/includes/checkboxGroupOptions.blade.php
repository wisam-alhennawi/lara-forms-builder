<div class="lfb-checkbox-group-item">
       <input wire:key="form-checkbox-group-component-{{ md5($key) }}"
              wire:model.live="formProperties.{{ $key }}"
              id="{{ $key }}.{{ $option['value'] }}"
              type="checkbox"
              value="{{ $option['value'] }}"
              class="lfb-checkbox @if(isset($option['disabled']) && $option['disabled']) lfb-disabled @endif"
              @if ((isset($mode) && ($mode == 'view' || $mode == 'confirm')) || (isset($option['disabled']) && $option['disabled'])) disabled @endif
       >
       <label class="lfb-label lfb-label-spacing print:text-xs" for="{{ $key }}.{{ $option['value'] }}">
              {!! $option['label'] !!}
       </label>
</div>
