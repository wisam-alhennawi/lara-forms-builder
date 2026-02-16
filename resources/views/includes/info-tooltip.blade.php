@php
    $tooltipText = is_array($message) ? ($message['text'] ?? '') : $message;
    $iconView = is_array($message) ? ($message['iconView'] ?? null) : null;

    $textLength = strlen(strip_tags($tooltipText));
    $widthClass = match (true) {
        $textLength <= 200 => 'lfb-info-tooltip-area-small-width',
        $textLength <= 400 => 'lfb-info-tooltip-area-medium-width',
        $textLength <= 500 => 'lfb-info-tooltip-area-large-width',
        default => 'lfb-info-tooltip-area-small-width',
    };
@endphp

<div class="lfb-info-tooltip-container"
    x-data="{ tooltipOpen: false }" @mouseenter="tooltipOpen = true" @mouseleave="tooltipOpen = false">
    <!-- Lucide circle-question-mark -->
    @if($iconView)
        @include($iconView)
    @else
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lfb-info-tooltip-icon">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 16v-4"/>
            <path d="M12 8h.01"/>
        </svg>
    @endif
    <span x-show="tooltipOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="lfb-info-tooltip-area {{ $widthClass }}">
        {{ $tooltipText }}
    </span>
</div>