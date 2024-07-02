<label for="formProperties-{{ $key }}" class="lfb-label">
    {!! $label !!}
    @if ((!isset($mode) || (isset($mode) and $mode != 'view')) && $this->isFieldRequired($key))
        <sup>*</sup>
    @endif
</label>
