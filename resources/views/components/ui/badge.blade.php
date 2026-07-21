@props(['tone' => 'gold'])

@php
    $map = [
        'gold'   => 'bp',
        'green'  => 'bh',
        'orange' => 'bd',
        'violet' => 'bf',
        'cyan'   => 'bm',
    ];
    $cls = $map[$tone] ?? 'bp';
@endphp

<span {{ $attributes->merge(['class' => 'badge-admin ' . $cls]) }}>{{ $slot }}</span>
