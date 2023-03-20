{{-- TODO: add css to config --}}
<div x-data="{ tab: @entangle('activeTab') }" class="bg-white shadow rounded-md">
    <div class="divide-y divide-gray-200 lg:grid lg:grid-cols-12 lg:divide-y-0 lg:divide-x">
        {{-- Tabs Links --}}
        <aside class="py-4 lg:col-span-2">
            <div class="flex flex-shrink-0 items-center px-6 mb-6">
                <div class="text-3xl font-semibold text-c_gray-800">{{ $formTitle }}</div>
            </div>
            <nav x-model="tab">
                @foreach($fields as $field)
                    <li x-bind:class="[ tab == '{{ $field['tab']['key'] }}' ? 'bg-c_gray-100 border-c_gray-300' : '']" class="text-c_gray-800 border-transparent hover:bg-c_gray-200/50 hover:border-c_gray-300 border-l-4 px-3 py-2 flex items-center text-sm font-medium cursor-pointer">
                        <a x-on:click.prevent="tab='{{ $field['tab']['key'] }}'">
                            {{ $field['tab']['title'] }}
                        </a>
                    </li>
                @endforeach
            </nav>
        </aside>

        {{-- Tabs Content --}}
        <div class="lg:col-span-10 relative" wire:target="save" wire:loading.class="opacity-50">
            <div class="relative">
                @foreach($fields as $fieldKey => $field)
                    <div x-show="tab == '{{ $field['tab']['key'] }}'" wire:key="{{ 'tab-'. md5($field['tab']['key']) }}" x-cloak class="py-6">
                        <div class="px-4">
                            <div>
                                <h2 class="text-xl font-normal leading-6 text-c_gray-800">{{ $field['tab']['title'] }}</h2>
                            </div>
                            @include('lara-forms-builder::includes.fields', [
                                    'fields' => [
                                        $fieldKey => $field['tab']['content']
                                    ]
                                ]
                            )
                        </div>
                    </div>
                @endforeach
            </div>

            @include('lara-forms-builder::includes.buttons')
        </div>
    </div>
</div>
