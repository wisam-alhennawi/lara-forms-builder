<div class="@if(isset($fieldWrapperClass)){{$fieldWrapperClass}}@endif">
    <label for="{{ $key }}" class="lfb-label">
        {{ $label }}
        @if ((!isset($mode) || (isset($mode) and $mode != 'view')) and isset($rules) and array_key_exists($key, $rules) && str_contains($rules[$key], 'required'))
            <sup>*</sup>
        @endif
    </label>
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
