{{-- Floating maximize control — parent must provide Alpine openPhoto() or openPreview(url) --}}
@props([
    'click' => 'openPhoto()',
    'label' => 'View full image',
])

<button type="button"
        {{ $attributes->merge(['class' => 'image-zoom-btn']) }}
        @click.stop="{{ $click }}"
        aria-label="{{ $label }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/>
    </svg>
</button>
