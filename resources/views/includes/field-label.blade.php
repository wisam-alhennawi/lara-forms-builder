<label for="{{ $key }}" class="lfb-label">
    {!! $label !!}
    @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules)
        and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
        <sup>*</sup>
    @endif
</label>