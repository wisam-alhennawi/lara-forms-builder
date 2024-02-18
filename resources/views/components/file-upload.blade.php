<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-input-wrapper lfb-input-readonly">
            @if ((isset($preview) && $preview) && ($model->$key || isset($this->{$key . '_preview'})))
                @if ($preview == 'image')
                    <img src="{{ $model->$key ? $model->$key->temporaryUrl() : $this->{$key . '_preview'} }}">
                @endif
            @else
                <input type="@if(isset($inputType)){{$inputType}}@else{{'text'}}@endif" name="{{ $key }}" id="{{ $key }}"
                value="@if ($model->$key || $this->$key){{ $model->$key ? $model->$key->getClientOriginalName() : $this->$key->getClientOriginalName() }}@else - @endif"
                class="lfb-input lfb-disabled" readonly disabled>
            @endif
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @else
        <div class="lfb-input-wrapper">
            <label class="block">
                @if(!$model->$key && !$this->$key && !isset($this->{$key . '_preview'}))
                <button type="button" class="lfb-input lfb-upload-button relative @if(isset($readOnly) && $readOnly) lfb-readonly @endif">
                    {{ __('Select File') }}
                    <input wire:key="form-input-component-{{ md5($key) }}" title="{{ __('Select File') }}"
                        name="{{ $key }}" id="{{ $key }}"
                        class="absolute start-0 w-full h-full opacity-0 cursor-pointer" type="file" wire:model="{{ $key }}"
                        @if(isset($readOnly) && $readOnly) readonly @endif>
                </button>
                @else
                    <span class="lfb-file-uploaded">
                        @if (isset($preview) && $preview)
                            @if ($preview == 'image')
                                @php
                                    $imagePath = $this->{$key . '_preview'} ?? $this->$key;
                                    if (is_object($imagePath)) {
                                        try {
                                            $imagePath = $imagePath->temporaryUrl();
                                        } catch (Exception $e) {
                                            $imagePath = null;
                                        }
                                    }
                                @endphp
                                @if (is_string($imagePath))
                                    <img src="{{ $imagePath }}">
                                @else
                                    {{ ($model->$key ?? $this->$key)->getClientOriginalName() }}
                                @endif
                            @endif
                        @else
                            {{ ($model->$key ?? $this->$key)->getClientOriginalName() }}
                        @endif
                        <a wire:click="resetValue('{{ $key}}')">
                        @if(isset($removeIcon))
                            {!! $removeIcon !!}
                        @else
                            <svg class="inline ml-1 -mt-1 cursor-pointer" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" height="16" stroke-width="1" aria-hidden="true">
                                <path d="M22.245,4.015c0.313,0.313,0.313,0.826,0,1.139l-6.276,6.27c-0.313,0.312-0.313,0.826,0,1.14l6.273,6.272  c0.313,0.313,0.313,0.826,0,1.14l-2.285,2.277c-0.314,0.312-0.828,0.312-1.142,0l-6.271-6.271c-0.313-0.313-0.828-0.313-1.141,0  l-6.276,6.267c-0.313,0.313-0.828,0.313-1.141,0l-2.282-2.28c-0.313-0.313-0.313-0.826,0-1.14l6.278-6.269  c0.313-0.312,0.313-0.826,0-1.14L1.709,5.147c-0.314-0.313-0.314-0.827,0-1.14l2.284-2.278C4.308,1.417,4.821,1.417,5.135,1.73  L11.405,8c0.314,0.314,0.828,0.314,1.141,0.001l6.276-6.267c0.312-0.312,0.826-0.312,1.141,0L22.245,4.015z"/>
                            </svg>
                        @endif
                        </a>
                    </span>
                @endif
            </label>
            @error($key) <span class="lfb-alert lfb-alert-error">{{ $message }}</span> @enderror
            @if(isset($formWarnings) && array_key_exists($key, $formWarnings))
                <span class="lfb-alert lfb-alert-warning">{{ $formWarnings[$key] }}</span>
            @endif
        </div>
    @endif
    @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
    @endif
</div>
