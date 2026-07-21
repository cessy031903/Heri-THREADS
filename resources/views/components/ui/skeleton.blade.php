@props([
    'w' => '100%',
    'h' => '1rem',
    'rounded' => 'var(--r-sm)',
])

<span
    aria-hidden="true"
    {{ $attributes->merge(['class' => 'ui-skeleton']) }}
    style="display:block;width:{{ $w }};height:{{ $h }};border-radius:{{ $rounded }};"
></span>
