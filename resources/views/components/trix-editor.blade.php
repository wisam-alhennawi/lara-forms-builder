<div class="{{ $fieldWrapperClass }}">
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-trix-editor-wrapper lfb-trix-editor-readonly">
            <input value="{{ $formProperties[$key] }}" id="trix-editor-input-content" type="hidden"/>
            <div wire:ignore>
                <trix-editor input="trix-editor-input-content" class="trix-editor"></trix-editor>
            </div>

            <script>
                document.querySelector('trix-editor').editor.element.setAttribute('contentEditable', false)
            </script>
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @else
        <div class="lfb-trix-editor-wrapper">
            <input id="trix-editor-input-content" type="hidden" value="{{ $formProperties[$key] }}"/>
            <div wire:ignore>
                <trix-editor input="trix-editor-input-content" class="trix-editor"></trix-editor>
            </div>
            <script>
                let trixEditor = document.getElementById("trix-editor-input-content")

                document.addEventListener("trix-change", function(event) {
                    @this.set("formProperties.{{ $key }}", trixEditor.getAttribute('value'));
                })
            </script>
            @include('lara-forms-builder::includes.field-error-message')
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @endif
    @include('lara-forms-builder::includes.field-help-text')
</div>
