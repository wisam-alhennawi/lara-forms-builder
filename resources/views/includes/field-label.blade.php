<label for="formProperties-{{ $key }}" class="lfb-label">
    <span>
        {!! $label !!}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')))
            {!! $this->fieldIndicator($key) !!}
        @endif
    </span>
    @if(isset($tooltip) && $tooltip)
        @include('lara-forms-builder::includes.tooltip', ['message' => $tooltip])
    @endif
</label>
