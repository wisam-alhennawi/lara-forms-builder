@php
    $controlledBy = $controlledBy ?? null;
    $controllerField = $controllerField ?? null;
    $useToggle = $useToggle ?? false;
    $isReadonlyMode = $isReadonlyMode ?? false;
    $toggleOptions = $controllerField['options'] ?? [0 => __('No'), 1 => __('Yes')];
    $toggleValues = array_keys($toggleOptions);
    $toggleClosedValue = $toggleValues[0] ?? 0;
    $toggleOpenValue = $toggleValues[1] ?? 1;
    $currentValue = $this->formProperties[$controlledBy] ?? $toggleClosedValue;
    if (is_bool($currentValue)) {
        $currentValue = $currentValue ? $toggleOpenValue : $toggleClosedValue;
    }
@endphp

@if ($controlledBy && is_array($controllerField))
    @if (! $useToggle)
        <input wire:key="form-checkbox-component-{{ md5($controlledBy) }}"
               wire:model.live="formProperties.{{ $controlledBy }}"
               x-model="open"
               id="formProperties-{{ $controlledBy }}"
               name="formProperties.{{ $controlledBy }}"
               type="checkbox"
               @class([
                   'lfb-checkbox',
                   'lfb-readonly' => $isReadonlyMode,
               ])
               @readonly($isReadonlyMode)
               @disabled($isReadonlyMode)
        >
    @else
        <div class="lfb-yes-no-toggle-container" role="group" aria-labelledby="label-{{ $controlledBy }}">
            <div class="lfb-yes-no-toggle-wrapper @if($isReadonlyMode) lfb-yes-no-toggle-disabled @endif">
                <button type="button"
                        class="lfb-yes-no-toggle-label lfb-yes-no-toggle-label-on {{ (string) $currentValue === (string) $toggleOpenValue ? 'lfb-yes-no-toggle-active' : '' }}"
                        wire:click="$set('formProperties.{{ $controlledBy }}', '{{ $toggleOpenValue }}')"
                    x-on:click="model = @js($toggleOpenValue); open = true"
                        @disabled($isReadonlyMode)
                        aria-pressed="{{ (string) $currentValue === (string) $toggleOpenValue ? 'true' : 'false' }}">
                    {{ $toggleOptions[$toggleOpenValue] ?? __('Yes') }}
                </button>

                <input type="hidden"
                       wire:model.live="formProperties.{{ $controlledBy }}"
                       id="formProperties-{{ $controlledBy }}"
                       name="formProperties.{{ $controlledBy }}"
                       value="{{ $currentValue }}">

                <button type="button"
                        class="lfb-yes-no-toggle-label lfb-yes-no-toggle-label-off {{ (string) $currentValue === (string) $toggleClosedValue ? 'lfb-yes-no-toggle-active' : '' }}"
                        wire:click="$set('formProperties.{{ $controlledBy }}', '{{ $toggleClosedValue }}')"
                    x-on:click="model = @js($toggleClosedValue); open = false"
                        @disabled($isReadonlyMode)
                        aria-pressed="{{ (string) $currentValue === (string) $toggleClosedValue ? 'true' : 'false' }}">
                    {{ $toggleOptions[$toggleClosedValue] ?? __('No') }}
                </button>
            </div>
        </div>
    @endif
@endif
