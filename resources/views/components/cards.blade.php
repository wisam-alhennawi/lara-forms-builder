<!-- TODO: add css to config file-->
@php
    $fieldValue = $model->$key->value ?? $model->$key;
@endphp
@error($key)
    <div class="{{$cardFieldErrorWrapperClass}}" role="alert">
        <div class="lfb-card-message-container">
            <div class="lfb-card-message-icon-container">
                <div class="lfb-card-message-icon-inner">
                    <x-heroicon-o-exclamation-circle />
                </div>
            </div>
            <div class="lfb-card-message-text">
                <div>
                    {{ $message }}
                </div>
            </div>
        </div>
    </div>
@enderror
<div>
    <div wire:key="form-cards-component-{{ md5($key) }}" class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
        @foreach ($selectOptions as $option)
            <div wire:click="$set('{{ $key }}', '{{ $option['value'] }}')"
            class="lfb-card-wrapper {{ $this->$key == $option['value'] ? 'lfb-card-wrapper--state-active' : '' }} {{($readOnly)? 'lfb-card-wrapper--state-read-only ':''}}">
                <div class="lfb-card-container @if(isset($icon)) with-icon @endif">
                    <div class="lfb-card-item">
                        <h4 class="lfb-card-label">
                            {!! $option['label'] !!}
                        </h4>
                    </div>
                    @if(isset($icon))
                    <div class="lfb-card-icon">
                        <div>
                            @svg($icon)
                        </div>
                    </div>
                    @endif  
                </div>
            </div>
        @endforeach
    </div>
    @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
    @endif
</div>

