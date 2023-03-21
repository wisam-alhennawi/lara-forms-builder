<!-- TODO: add css to config file-->
@php
    $fieldValue = $model->$key->value ?? $model->$key;
@endphp
@error($key)
    <div class="{{$cardFieldErrorWrapperClass}}" role="alert">
        <div class="flex">
            <div class="bg-yellow-500 w-14 text-center p-2">
                <div class="flex justify-center h-full items-center">
                    <x-heroicon-o-exclamation-circle  class="h-6 w-6 text-white" />
                </div>
            </div>
            <div class="bg-gray-100 border-r-4 border-yellow-500 w-full p-4">
                <div>
                    <p class="text-gray-600 text-sm">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
@enderror
<div wire:key="form-cards-component-{{ md5($key) }}" class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    @foreach ($selectOptions as $option)
        <div wire:click="$set('{{ $key }}', '{{ $option['value'] }}')"
        class="bg-c_gray-100 rounded-md relative p-4 mt-2 flex items-center border border-c_gray-300 {{ $this->$key == $option['value'] ? 'border-status-green hover:-translate-y-0' : 'border-c_gray-300' }} {{($readOnly)? 'text-c_gray-400/40 pointer-events-none':'transition-all hover:shadow-md hover:-translate-y-0.5 shadow cursor-pointer'}}">
            <div class="flex gap-2 items-center justify-between w-full">
                <div class="w-10/12">
                    <div class="flex flex-col gap-1 items-start justify-center">
                        <h4 class="text-base leading-6">
                            {{ $option['label'] }}
                        </h4>
                    </div>
                </div>
                <div class="w-2/12 flex justify-center items-center {{($readOnly)?'text-status-green/40':'text-status-green'}}">
                    <x-heroicon-o-table-cells class="w-10 h-10" />
                </div>
            </div>
        </div>
    @endforeach
</div>
