<div x-data="{ tab: @entangle('activeTab').live }" class="lfb-tabs-wrapper">
    <div class="lfb-tab-container">
        {{-- Tabs Links --}}
        @if($isMultiStep)
            <aside class="lfb-steps-nav-wrapper">
                <div class="lfb-steps-nav-container">
                    <nav class="lfb-steps-nav">
                        @foreach($fields as $field)
                            <div class="lfb-step-nav">
                                <div x-bind:class="[ tab == '{{ $field['key'] }}' ? 'lfb-step-nav-title-active' : '']" class="lfb-step-nav-title">
                                    {{ $field['title'] }}
                                </div>
                            </div>
                        @endforeach
                    </nav>
                </div>
            </aside>
        @else
            <aside class="lfb-tab-nav-wrapper">
                <div class="lfb-tab-nav-container">
                    @if(!empty($formTitle))
                        <div class="lfb-tab-nav-title-wrapper">
                            <div class="lfb-tab-nav-title">{{ $formTitle }}</div>
                        </div>
                    @endif
                    <nav class="lfb-tab-nav-links" x-model="tab">
                        @foreach($fields as $field)
                            <div class="lfb-tab-nav-link-item">
                                <a x-bind:class="[ tab == '{{ $field['key'] }}' ? 'lfb-tab-nav-link-active' : '']" class="lfb-tab-nav-link" x-on:click.prevent="tab='{{ $field['key'] }}'">
                                    {{ $field['title'] }}
                                </a>
                            </div>
                        @endforeach
                    </nav>
                </div>
            </aside>
        @endif

        {{-- Tabs Content --}}
        <div class="lfb-tab-content-wrapper" wire:target="save" wire:loading.class="lfb-tab-content-wrapper-loading-class">
            <div class="lfb-tab-content-container">
                @error('tabWarning')
                    <div class="lfb-tab-error-wrapper" role="alert">
                        <div class="lfb-tab-error-container">
                            <div class="lfb-tab-error-icon flex justify-center items-center">
                                @if(isset($tabWarningIcon))
                                    {!! $tabWarningIcon !!}
                                @else
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                     <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                                 </svg>
                                @endif
                            </div>
                            <div class="lfb-tab-error-message">
                                <p>{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @enderror
                @foreach($fields as $fieldKey => $field)
                    <div x-show="tab == '{{ $field['key'] }}'" wire:key="{{ 'tab-'. md5($field['key']) }}" x-cloak class="lfb-tab-content-item">
                            <h2 class="lfb-tab-content-item-title">{{ $field['title'] }}</h2>
                            @include('lara-forms-builder::includes.fields', [
                                    'fields' => [
                                        $fieldKey => $field['content']
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
