@php
    $variant = $variant ?? 'default';
    $alpineModel = $alpineModel ?? null;
    $values = array_keys($toggleOptions);
    $noValue = $values[0] ?? 0;
    $yesValue = $values[1] ?? 1;
    $noLabel = $toggleOptions[$noValue] ?? __('No');
    $yesLabel = $toggleOptions[$yesValue] ?? __('Yes');
    
    // Normalize current value to string for consistent comparison, especially when dealing with boolean values
    $currentValue = $this->formProperties[$key] ?? $noValue;
    if (is_bool($currentValue)) {
        $currentValue = $currentValue ? $yesValue : $noValue;
    }
    
    // Manage disabled state
    $isDisabled = (isset($mode) && ($mode == 'view' || $mode == 'confirm')) || $readOnly;
@endphp

@if ($variant === 'accordion')
    <div class="lfb-yes-no-toggle-container" 
         role="group" 
         aria-labelledby="label-{{ $key }}">
        <div class="lfb-yes-no-toggle-wrapper @if($isDisabled) lfb-yes-no-toggle-disabled @endif">
            <!-- Button Yes -->
            <button type="button"
                    class="lfb-yes-no-toggle-label lfb-yes-no-toggle-label-on {{ (string) $currentValue === (string) $yesValue ? 'lfb-yes-no-toggle-active' : '' }}"
                    wire:click="$set('formProperties.{{ $key }}', '{{ $yesValue }}')"
                    @if($alpineModel) x-on:click="{{ $alpineModel }} = true" @endif
                    @disabled($isDisabled)
                    aria-pressed="{{ (string) $currentValue === (string) $yesValue ? 'true' : 'false' }}">
                {{ $yesLabel }}
            </button>
            
            <!-- Hidden input to maintain form state -->
            <input type="hidden" 
                   wire:model.live="formProperties.{{ $key }}"
                   id="formProperties-{{ $key }}"
                   name="formProperties.{{ $key }}"
                   value="{{ $currentValue }}">
            
            <!-- Button No -->
            <button type="button"
                    class="lfb-yes-no-toggle-label lfb-yes-no-toggle-label-off {{ (string) $currentValue === (string) $noValue ? 'lfb-yes-no-toggle-active' : '' }}"
                    wire:click="$set('formProperties.{{ $key }}', '{{ $noValue }}')"
                    @if($alpineModel) x-on:click="{{ $alpineModel }} = false" @endif
                    @disabled($isDisabled)
                    aria-pressed="{{ (string) $currentValue === (string) $noValue ? 'true' : 'false' }}">
                {{ $noLabel }}
            </button>
        </div>
    </div>
@else
    <div class="{{ $fieldWrapperClass }} @error('formProperties.' . $key){{ $fieldErrorWrapperClass }}@enderror">  
        @include('lara-forms-builder::includes.field-label')
        <div class="lfb-yes-no-toggle-container" 
             role="group" 
             aria-labelledby="label-{{ $key }}"
             aria-describedby="@if($helpText)help-{{ $key }}@endif">
            
            <div class="lfb-yes-no-toggle-wrapper @if($isDisabled) lfb-yes-no-toggle-disabled @endif">
                
                <!-- Button Yes -->
                <button type="button"
                        class="lfb-yes-no-toggle-label lfb-yes-no-toggle-label-on {{ (string) $currentValue === (string) $yesValue ? 'lfb-yes-no-toggle-active' : '' }}"
                        wire:click="$set('formProperties.{{ $key }}', '{{ $yesValue }}')"
                        @disabled($isDisabled)
                        aria-pressed="{{ (string) $currentValue === (string) $yesValue ? 'true' : 'false' }}">
                    {{ $yesLabel }}
                </button>
                
                <!-- Hidden input to maintain form state -->
                <input type="hidden" 
                       wire:model.live="formProperties.{{ $key }}"
                       id="formProperties-{{ $key }}"
                       name="formProperties.{{ $key }}"
                       value="{{ $currentValue }}">
                
                <!-- Button No -->
                <button type="button"
                        class="lfb-yes-no-toggle-label lfb-yes-no-toggle-label-off {{ (string) $currentValue === (string) $noValue ? 'lfb-yes-no-toggle-active' : '' }}"
                        wire:click="$set('formProperties.{{ $key }}', '{{ $noValue }}')"
                        @disabled($isDisabled)
                        aria-pressed="{{ (string) $currentValue === (string) $noValue ? 'true' : 'false' }}">
                    {{ $noLabel }}
                </button>
                
            </div>
        </div>
        
        @include('lara-forms-builder::includes.field-error-message')
        @include('lara-forms-builder::includes.field-help-text')
    </div>
@endif