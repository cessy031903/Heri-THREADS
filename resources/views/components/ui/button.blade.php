@props([
    'variant' => 'primary',   // primary | outline | danger
    'size' => null,           // null | sm
    'type' => 'button',
])

@php
    $classes = 'btn-admin btn-admin-' . $variant;
    if ($size === 'sm') {
        $classes .= ' btn-admin-sm';
    }
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
