<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    @include('lara-forms-builder::includes.field-label')
    <div class="lfb-fieldset lfb-fieldset-container">
        <div class="lfb-checkbox-group">
            @if($hasCategory)
                @foreach($checkboxGroupOptions as $category => $options)
                    <div class="lfb-checkbox-category-group">
                        <label for="formProperties-{{ $key }}" class="lfb-checkbox-category-group-label">
                            {{ $category }}
                        </label>
                        @foreach($options as $option)
                            @include('lara-forms-builder::includes.checkboxGroupOptions')
                        @endforeach
                    </div>
                @endforeach
            @else
                @foreach($checkboxGroupOptions as $option)
                    @include('lara-forms-builder::includes.checkboxGroupOptions')
                @endforeach
            @endif
        </div>
    </div>
    @include('lara-forms-builder::includes.field-error-message')
    @include('lara-forms-builder::includes.field-help-text')
</div>
