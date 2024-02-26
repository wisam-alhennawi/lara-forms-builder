@if(isset($formWarnings) && array_key_exists($key, $formWarnings))
    <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
@endif
