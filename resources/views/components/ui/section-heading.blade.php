@props([
    'eyebrow' => null,
    'title' => null,
    'sub' => null,
    'align' => 'left',   // left | center
])

<div {{ $attributes->merge(['class' => 'ui-heading ui-heading-' . $align]) }}>
    @if($eyebrow)
        <span class="ui-heading-eyebrow">{{ $eyebrow }}</span>
    @endif
    @if($title)
        <h2 class="ui-heading-title">{{ $title }}</h2>
    @endif
    @if($sub)
        <p class="ui-heading-sub">{{ $sub }}</p>
    @endif
    {{ $slot }}
</div>
