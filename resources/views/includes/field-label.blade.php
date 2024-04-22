<label for="formProperties-{{ $key }}" class="lfb-label">
    {!! $label !!}
    @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules)
        and array_key_exists('formProperties.' .  $key, $rules) && str_contains($rules['formProperties.' .  $key], 'required'))
        <sup>*</sup>
    @endif
    @if(isset($tooltip) && $tooltip)
        @include('lara-forms-builder::includes.tooltip', ['message' => $tooltip])
    @endif
</label>
