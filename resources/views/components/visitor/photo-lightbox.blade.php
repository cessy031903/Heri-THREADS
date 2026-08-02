{{-- Full-screen image viewer — parent Alpine: previewUrl + closePreview() --}}
@props([
    'show' => 'previewUrl',
    'close' => 'closePreview()',
    'src' => 'previewUrl',
    'ariaLabel' => 'Full size photo',
])

<div class="photo-lightbox"
     x-show="{{ $show }}"
     x-cloak
     x-transition.opacity
     @click.self="{{ $close }}"
     role="dialog"
     aria-modal="true"
     aria-label="{{ $ariaLabel }}">
    <button type="button" class="photo-lightbox-close" @click="{{ $close }}" aria-label="Close">✕</button>
    <img :src="{{ $src }}" alt="" class="photo-lightbox-img" />
</div>
