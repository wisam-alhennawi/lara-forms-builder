<div class="{{ $fieldWrapperClass }}">
    @include('lara-forms-builder::includes.field-label')
    @if (isset($mode) && ($mode == 'view' || $mode == 'confirm'))
        <div class="lfb-trix-editor-wrapper lfb-trix-editor-readonly">
            <input id="trix-editor-input-content-{{ $key }}"
                   value="{{ $formProperties[$key] }}"
                   type="hidden" />

            <div wire:ignore>
                <trix-editor input="trix-editor-input-content-{{ $key }}" class="trix-editor"></trix-editor>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const readonlyEditor = document.querySelector('trix-editor[input="trix-editor-input-content-{{ $key }}"]');
                    readonlyEditor.editor.element.setAttribute('contentEditable', false);
                });
            </script>
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @else
        <div class="lfb-trix-editor-wrapper">
            <input id="trix-editor-input-content-{{ $key }}"
                   value="{{ $formProperties[$key] }}"
                   type="hidden" />

            <div wire:ignore>
                <trix-editor input="trix-editor-input-content-{{ $key }}" class="trix-editor"></trix-editor>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const input = document.getElementById("trix-editor-input-content-{{ $key }}");
                    const editor = document.querySelector('trix-editor[input="trix-editor-input-content-{{ $key }}"]');

                    editor.addEventListener("trix-change", function () {
                        @this.set("formProperties.{{ $key }}", input.value);
                    });
                });
            </script>
            @include('lara-forms-builder::includes.field-error-message')
            @include('lara-forms-builder::includes.field-form-warning')
        </div>
    @endif
    @include('lara-forms-builder::includes.field-help-text')
</div>
