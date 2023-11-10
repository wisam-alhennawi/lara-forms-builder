{{-- TODO: add css to config --}}
<div x-data="{ tab: @entangle('activeTab') }" class="lfb-tabs-wrapper">
    <div class="lfb-tab-container">
        {{-- Tabs Links --}}
        <aside class="lfb-tab-nav-wrapper">
            <div class="lfb-tab-nav-container">
                <div class="lfb-tab-nav-title-wrapper">
                    <div class="lfb-tab-nav-title">{{ $formTitle }}</div>
                </div>
                <nav class="lfb-tab-nav-links" x-model="tab">
                    @foreach($fields as $field)
                        <div class="lfb-tab-nav-link-item">
                            <a x-bind:class="[ tab == '{{ $field['tab']['key'] }}' ? 'lfb-tab-nav-link-active' : '']" class="lfb-tab-nav-link" x-on:click.prevent="tab='{{ $field['tab']['key'] }}'">
                                {{ $field['tab']['title'] }}
                            </a>
                        </div>
                    @endforeach
                </nav>
            </div>
        </aside>

        {{-- Tabs Content --}}
        <div class="lfb-tab-content-wrapper" wire:target="save" wire:loading.class="lfb-tab-content-wrapper-loading-class">
            <div class="lfb-tab-content-container">
                @error('tabWarning')
                    <div class="lfb-tab-error-wrapper" role="alert">
                        <div class="lfb-tab-error-container">
                            <div class="lfb-tab-error-icon flex justify-center items-center">
                                <x-heroicon-o-exclamation-circle  />
                            </div>
                            <div class="lfb-tab-error-message">
                                <p>{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @enderror
                @foreach($fields as $fieldKey => $field)
                    <div x-show="tab == '{{ $field['tab']['key'] }}'" wire:key="{{ 'tab-'. md5($field['tab']['key']) }}" x-cloak class="lfb-tab-content-item">
                            <h2 class="lfb-tab-content-item-title">{{ $field['tab']['title'] }}</h2>
                            @include('lara-forms-builder::includes.fields', [
                                    'fields' => [
                                        $fieldKey => $field['tab']['content']
                                    ]
                                ]
                            )
                    </div>
                @endforeach
            </div>
            @include('lara-forms-builder::includes.buttons')
        </div>
    </div>
</div>
