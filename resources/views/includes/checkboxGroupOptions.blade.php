<div class="lfb-checkbox-group-item">
    <input wire:key="form-checkbox-group-component-{{ md5($key) }}" type="checkbox"
           id="{{ $option['value'] }}" value="{{ $option['value'] }}"
           wire:model="{{ $key }}" class="lfb-checkbox"
           @if (isset($mode) && ($mode == 'view' || $mode == 'confirm')) disabled @endif>
    <label class="lfb-label lfb-label-spacing print:text-xs"
           for="{{ $option['value'] }}">{{ $option['label'] }}
    </label>
</div>
