@foreach ($fields as $fieldKey => $field)
    @if (is_numeric($fieldKey) && isset($field['fields']))
        @php
            $groupMeta = $this->resolveGroupMeta($field, $groupWrapperClass, $mode ?? null);
            $groupInfo = $groupMeta['groupInfo'];
            $isAccordion = $groupMeta['isAccordion'];
            $controlledBy = $groupMeta['controlledBy'];
            $controllerField = $groupMeta['controllerField'];
            $useToggle = $groupMeta['useToggle'];
            $initialAccordionValue = $groupMeta['initialAccordionValue'];
            $initialAccordionOpen = $groupMeta['initialAccordionOpen'];
            $accordionOpenValue = $groupMeta['accordionOpenValue'];
            $isReadonlyMode = $groupMeta['isReadonlyMode'];
            $resolvedGroupWrapperClass = $groupMeta['resolvedGroupWrapperClass'];
        @endphp

        <div class="lfb-fields-wrapper @if($isAccordion) lfb-accordion-wrapper @endif"
             @if($isAccordion && $controlledBy)
                x-data="{ open: @entangle('formProperties.' . $controlledBy).live }"
                x-init="
                    if ((open === null || open === undefined || open === '') && @js($initialAccordionValue !== null)) {
                        open = @js($initialAccordionValue)
                    }
                "
            @endif>
            @if (isset($groupInfo['title']) || isset($groupInfo['description']) || isset($groupInfo['description_view']))
                <div class="lfb-group-header-wrapper">
                    @if ($isAccordion && $controlledBy && $controllerField)
                        <fieldset>
                            <div class="lfb-fieldset-header-accordion-container">
                                <label class="lfb-group-accordion-title" for="formProperties-{{ $controlledBy }}">
                                    {!! $groupInfo['title'] ?? '' !!}
                                    @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules)
                                        and array_key_exists('formProperties.' .  $controlledBy, $rules) && str_contains($rules['formProperties.' .  $controlledBy], 'required'))
                                        <sup>*</sup>
                                    @endif
                                </label>
                                @include('lara-forms-builder::includes.accordion-controller', [
                                    'controlledBy' => $controlledBy,
                                    'controllerField' => $controllerField,
                                    'useToggle' => $useToggle,
                                    'isReadonlyMode' => $isReadonlyMode,
                                ])
                            </div>
                            @error('formProperties.' .  $controlledBy)
                                <span class="lfb-alert lfb-alert-error">
                                    {{ $message }}
                                </span>
                            @enderror
                            @if (isset($groupInfo['description']))
                                <p class="lfb-group-description">
                                    {{ $groupInfo['description'] }}
                                </p>
                            @endif
                            @if (isset($groupInfo['description_view']))
                                @include($groupInfo['description_view'])
                            @endif
                        </fieldset>
                    @else
                        @if (isset($groupInfo['title']))
                            <h2 class="lfb-group-title">
                                {{ $groupInfo['title'] }}
                            </h2>
                        @endif
                        @if (isset($groupInfo['description']))
                            <p class="lfb-group-description">
                                {{ $groupInfo['description'] }}
                            </p>
                        @endif
                        @if (isset($groupInfo['description_view']))
                            @include($groupInfo['description_view'])
                        @endif
                    @endif
                </div>
            @endif

                <div
                    @if($isAccordion && $controlledBy && $controllerField)
                        x-show="open"
                        @if(!$initialAccordionOpen) x-cloak @endif
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                    @endif
                    class="{{ $resolvedGroupWrapperClass }}"
                >
                @foreach ($field['fields'] as $groupFieldKey => $groupField)
                    @php
                        $resolvedGroupFieldKey = is_array($groupField)
                            ? (is_string($groupFieldKey) ? $groupFieldKey : ($groupField['key'] ?? null))
                            : null;
                        $isControllerField = $isAccordion && $controlledBy && $resolvedGroupFieldKey === $controlledBy;
                    @endphp
                    @if (! $resolvedGroupFieldKey || $isControllerField)
                        @continue
                    @endif
                    @include('lara-forms-builder::form-components', [
                        'field' => $groupField,
                        'fieldKey' => $resolvedGroupFieldKey,
                        'defaultFieldWrapperClass' => $defaultFieldWrapperClass
                    ])
                @endforeach
            </div>
        </div>
    @else
        @include('lara-forms-builder::form-components', [
            'field' => $field,
            'fieldKey' => $fieldKey,
            'defaultFieldWrapperClass' => $defaultFieldWrapperClass
        ])
    @endif
@endforeach
