<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    @include('lara-forms-builder::includes.field-label')
    <div class="lfb-fieldset lfb-fieldset-container">
        <div class="lfb-checkbox-group">
            @if($hasCategory)
                @foreach($checkboxGroupOptions as $category => $options)
                    <div class="lfb-checkbox-category-group">
                        <label for="{{ $key }}" class="lfb-checkbox-category-group-label">
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
    @error($key) <span class="lfb-alert lfb-alert-error">{{ $message }}</span> @enderror
    @if(isset($helpText) && $helpText)
        <p class="lfb-help-text">{{ $helpText }}</p>
    @endif
</div>
