
@php $lfbGroupKeyCounter = []; @endphp
@foreach ($fields as $fieldKey => $field)
    <?php if (is_numeric($fieldKey) && isset($field['fields'])): ?>
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
            $groupFieldsKeys = array_keys($field['fields'] ?? []);
            $baseGroupKey = implode('|', $groupFieldsKeys);
            $lfbGroupKeyCounter[$baseGroupKey] = ($lfbGroupKeyCounter[$baseGroupKey] ?? 0) + 1;
            $groupWireKey = 'lfb-group-'. md5((string) $baseGroupKey . '-' . $lfbGroupKeyCounter[$baseGroupKey]);
        @endphp

        <div wire:key="{{ $groupWireKey }}" class="lfb-fields-wrapper @if($isAccordion) lfb-accordion-wrapper @endif"
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

                        $isRepeaterEnabled = isset($groupMeta['groupInfo']['repeater']['group_id']);
                        if ($isRepeaterEnabled) {
                            $groupId = $groupMeta['groupInfo']['repeater']['group_id'];
                            if (isset($this->hasTabs) && $this->hasTabs === true) {
                                $repeatedGroups = collect();
                                $lastGroupIndex = null;
                                foreach ($this->fields as $index => $tab) {
                                    foreach ($tab['content'] as $groupIndex => $group) {
                                        if (isset($group['group_info']['repeater']['group_id']) && $group['group_info']['repeater']['group_id'] === $groupId) {
                                            $repeatedGroups[$groupIndex] = $group;
                                            $lastGroupIndex = $groupIndex;
                                        }
                                    }
                                }
                            } else {
                                $repeatedGroups = collect($this->fields)
                                    ->filter(fn($group) => isset($group['group_info']['repeater']['group_id']) && $group['group_info']['repeater']['group_id'] === $groupId);
                            }

                            // Get the repeated groups count to conditionally display the delete button
                            $repeatedGroupsCount = $repeatedGroups->count();
                            // Check if this is the last repeated group to conditionally display the Add button below it
                            $isLastGroup = $fieldKey === $repeatedGroups->keys()->last();
                        }
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
                @if($isRepeaterEnabled && ! in_array($this->mode, ['view', 'confirm']) && $isLastGroup)
                    <div class="lfb-repeater-buttons-wrapper">
                        {{-- Add button --}}
                        <button type="button"
                                wire:click="processGroupRepeating('{{ $field['group_info']['repeater']['group_id'] }}')"
                                class="lfb-repeater-add-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>

                    {{-- Delete button --}}
                    @if($repeatedGroupsCount > 1)
                        <button type="button"
                                wire:click="deleteRepeatedGroup('{{ $fieldKey }}', '{{ $field['group_info']['repeater']['group_id'] }}')"
                                class="lfb-repeater-remove-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    <?php else: ?>
        @include('lara-forms-builder::form-components', [
            'field' => $field,
            'fieldKey' => $fieldKey,
            'defaultFieldWrapperClass' => $defaultFieldWrapperClass
        ])
    <?php endif; ?>
@endforeach
