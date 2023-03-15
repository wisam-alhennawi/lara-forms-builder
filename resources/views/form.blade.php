@php
    $groupWrapperClass = $this->getDefaultGroupWrapperClass();
    $defaultFieldWrapperClass = $this->getDefaultFieldWrapperClass();
@endphp

<div id="lara-forms-builder">
    @if ($headView)
        @include($headView)
    @endif
    @foreach ($fields as $fieldKey => $field)
        @if (is_numeric($fieldKey) && isset($field['fields']))
            <div class="{{ array_key_last($this->fields()) === $fieldKey ? 'pt-4' : 'py-4 border-b' }}">
                @if (isset($field['group_info']))
                    @if (isset($field['group_info']['title']) || isset($field['group_info']['description']) || isset($field['group_info']['description_view']))
                        <div>
                            @if (isset($field['group_info']['title']))
                                <h2 class="text-xl font-normal leading-6 text-gray-800">
                                    {{ $field['group_info']['title'] }}
                                </h2>
                            @endif
                            @if (isset($field['group_info']['description']))
                                <p class="mt-1 text-sm text-gray-500">
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
            </div>
        @else
            @include('lara-forms-builder::form-components', [
                'field' => $field,
                'fieldKey' => $fieldKey,
                'defaultFieldWrapperClass' => $defaultFieldWrapperClass
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

    <div class="border-t border-gray-200 mt-8 pt-6">
        <div class="px-4 flex justify-end gap-2">
            <button wire:click="cancel" class="{{$this->getSecodaryButtonClasses()}}">
                {{ $cancelButtonLabel }}
            </button>
            @if (isset($mode) && $mode == 'view')
                @can('update', $model)
                <button wire:click="switchToEditMode" class="{{$this->getPrimaryButtonClasses()}}">
                    {{ __('Edit') }}
                </button>
                @endcan
            @else
            <button wire:click="checkAndSave" type="button" class="{{$this->getPrimaryButtonClasses()}}">
                {{ $submitButtonLabel }}
            </button>
            @endif
        </div>
    </div>
</div>
