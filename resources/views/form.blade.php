<div>
    @if ($headView)
        @include($headView)
    @endif

    <div class="p-4">
        @foreach ( $this->fields() as $fieldKey => $field)
            @if (is_numeric($fieldKey) && isset($field['fields']))
                <div class="{{ array_key_last($this->fields()) === $fieldKey ? '' : 'border-b' }} mb-4">
                    @if (isset($field['group_info']))
                        @if (isset($field['group_info']['title']))
                        <h3 class="text-lg font-medium leading-6 text-gray-900 py-4">
                            {{ $field['group_info']['title'] }}
                        </h3>
                        @endif
                        @if (isset($field['group_info']['description_view']))
                            @include($field['group_info']['description_view'])
                        @endif
                    @endif
                    @php
                        $groupWrapperClass = $this->getDefaultGroupWrapperClass();
                        $customGroupWrapperClass = isset($field['group_info']['group_wrapper_class']) ? $field['group_info']['group_wrapper_class'] : null;
                        $isGroupWrapperClassDefault = isset($field['group_info']['default_group_wrapper_class']) ? $field['group_info']['default_group_wrapper_class'] : true;
                        if (!empty($customGroupWrapperClass)) {
                            $groupWrapperClass = !$isGroupWrapperClassDefault ? $customGroupWrapperClass : $groupWrapperClass . ' ' . $customGroupWrapperClass;
                        }
                    @endphp
                        <div class="{{$groupWrapperClass}}">
                    @foreach ($field['fields'] as $groupFieldKey => $groupField)
                        @include('lara-forms-builder::form-components', [
                            'field' => $groupField,
                            'fieldKey' => $groupFieldKey
                        ])
                    @endforeach
                        </div>
                </div>
            @else
                @include('lara-forms-builder::form-components', [
                    'field' => $field,
                    'fieldKey' => $fieldKey
                ])
            @endif
        @endforeach

        @error('formSubmit')
        <div class="rounded-md bg-red-50 p-4 mb-4">
            <h3 class="text-sm font-medium text-red-800">{{ __('Error on submit') }}</h3>
            <div class="mt-2 text-sm text-red-700">
                <p>{{ $message }}</p>
            </div>
        </div>
        @enderror

        <div class="mt-5">
            @if (isset($mode) && $mode == 'view')
                @can('update', $model)
                <x-jet-button wire:click="switchToEditMode">
                    {{ __('Edit') }}
                </x-jet-button>
                @endcan
            @else
            <x-jet-button wire:click="checkAndSave" type="button">
                {{ $submitButtonLabel }}
            </x-jet-button>
            @endif

            <x-jet-secondary-button wire:click="cancel">
                {{ $cancelButtonLabel }}
            </x-jet-secondary-button>
        </div>

    </div>
</div>
