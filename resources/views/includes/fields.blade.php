@foreach ($fields as $fieldKey => $field)
    @if (is_numeric($fieldKey) && isset($field['fields']))
        <div class="lfb-fields-wrapper">
            @if (isset($field['group_info']))
                @if (isset($field['group_info']['title']) || isset($field['group_info']['description']) || isset($field['group_info']['description_view']))
                    <div>
                        @if (isset($field['group_info']['title']))
                            <h2 class="lfb-group-title">
                                {{ $field['group_info']['title'] }}
                            </h2>
                        @endif
                        @if (isset($field['group_info']['description']))
                            <p class="lfb-group-description">
                                {{ $field['group_info']['description'] }}
                            </p>
                        @endif
                        @if (isset($field['group_info']['description_view']))
                            @include($field['group_info']['description_view'])
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
                $isRepeaterEnabled = isset($field['group_info']['repeater']) && $field['group_info']['repeater'] === true;
                // Count only repeater blocks (keys with prefix $this->groupRepeaterPrefix)
                $lastIndex = collect($this->fields[$fieldKey]['fields'])
                    ->keys()
                    ->filter(fn($Key) => str_starts_with($Key, $this->groupRepeaterPrefix))
                    ->map(fn($key) => (int) substr($key, strrpos($key, '_') + 1))
                    ->max();
            @endphp
                    <div class="{{$groupWrapperClass}}">
                        @foreach ($field['fields'] as $groupFieldKey => $groupField)
                            @include('lara-forms-builder::form-components', [
                                'field' => $groupField,
                                'fieldKey' => $groupFieldKey,
                                'defaultFieldWrapperClass' => $defaultFieldWrapperClass
                            ])
                        @endforeach
                    </div>
                    @if(! in_array($this->mode, ['view', 'confirm']) && $isRepeaterEnabled)
                    <div class="lfb-repeater-buttons-wrapper">
                        {{-- Add button --}}
                        <button type="button"
                                wire:click="processGroupRepeating('{{ $field['group_info']['group-id'] }}')"
                                class="lfb-repeater-add-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>

                        {{-- Remove button --}}
                        @if($lastIndex > 0)
                        <button type="button"
                                wire:click="removeRepeater('{{ $fieldKey }}')"
                                class="lfb-repeater-remove-button flex items-center gap-1 px-3 py-1 text-sm font-medium text-red-700 bg-red-100 rounded hover:bg-red-200 transition">
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
    @else
        @include('lara-forms-builder::form-components', [
            'field' => $field,
            'fieldKey' => $fieldKey,
            'defaultFieldWrapperClass' => $defaultFieldWrapperClass
        ])
    @endif
@endforeach
