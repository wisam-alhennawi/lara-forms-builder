@foreach ($fields as $fieldKey => $field)
    @if (is_numeric($fieldKey) && isset($field['fields']))
        <div class="lfb-fields-wrapper @if(isset($field['group_info']['accordion']) && $field['group_info']['accordion'])lfb-accordion-wrapper @endif" @if(isset($field['group_info']) && (bool) ($field['group_info']['accordion'] ?? false) && isset($field['group_info']['key'])) x-data="{ open: @entangle('formProperties.' . $field['group_info']['key']).live }" @endif>
            @if (isset($field['group_info']))
                @if (isset($field['group_info']['title']) || isset($field['group_info']['description']) || isset($field['group_info']['description_view']))
                    @php
                        $groupInfo = $field['group_info'];
                        $isAccordion = (bool) ($groupInfo['accordion'] ?? false);
                        $accordionKey = $groupInfo['key'] ?? null;
                        $isReadonlyMode = (isset($mode) && ($mode == 'view' || $mode == 'confirm')) || ($groupInfo['readOnly'] ?? false);
                    @endphp
                    <div class="lfb-group-header-wrapper">
                        @if ($isAccordion && $accordionKey)
                            <fieldset>
                                <div class="lfb-fieldset-header-accordion-container">
                                    <label class="lfb-group-accordion-title" for="formProperties-{{ $accordionKey }}">
                                        {!! $groupInfo['title'] ?? '' !!}
                                        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules)
                                            and array_key_exists('formProperties.' .  $accordionKey, $rules) && str_contains($rules['formProperties.' .  $accordionKey], 'required'))
                                            <sup>*</sup>
                                        @endif
                                    </label>
                                    <input wire:key="form-group-info-accordion-component-{{ md5($accordionKey) }}"
                                            wire:model.live="formProperties.{{ $accordionKey }}"
                                            x-model="open"
                                            id="formProperties-{{ $accordionKey }}"
                                            name="formProperties.{{ $accordionKey }}"
                                            type="checkbox"
                                            @class([
                                                'lfb-checkbox',
                                                'lfb-readonly' => $isReadonlyMode,
                                            ])
                                            @readonly($isReadonlyMode)
                                            @disabled($isReadonlyMode)
                                    >
                                </div>
                                @error('formProperties.' .  $accordionKey)
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
            @endif
            @php
                $customGroupWrapperClass = isset($field['group_info']['group_wrapper_class']) ? $field['group_info']['group_wrapper_class'] : null;
                $isGroupWrapperClassDefault = isset($field['group_info']['default_group_wrapper_class']) ? $field['group_info']['default_group_wrapper_class'] : true;
                if (!empty($customGroupWrapperClass)) {
                    $groupWrapperClass = !$isGroupWrapperClassDefault ? $customGroupWrapperClass : $groupWrapperClass . ' ' . $customGroupWrapperClass;
                }
            @endphp
                <div @if(isset($isAccordion) && $isAccordion && isset($accordionKey) && $accordionKey) 
                        x-show="open" 
                        x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        :class="open ? 'lfb-group-open' : ''"
                    @endif 
                    class="{{$groupWrapperClass}}" 
                    >
                    @foreach ($field['fields'] as $groupFieldKey => $groupField)
                        @include('lara-forms-builder::form-components', [
                            'field' => $groupField,
                            'fieldKey' => $groupFieldKey,
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
