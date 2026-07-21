@props([
    'model',                 // Livewire boolean property name, e.g. "showModal"
    'title' => null,
    'lg' => false,
    'maxWidth' => null,
])

{{--
  Bare modal: just a blurred backdrop and the form. No animations, no
  transitions, no opacity tricks — so the box is always painted.
  Visibility is driven entirely by the server (`@if($model)` in the consumer).
  The only JS is a body scroll-lock tied to the element lifecycle.
--}}
<div
    class="modal-ov"
    wire:key="modal-{{ $model }}"
    x-data="{
        init()    { document.documentElement.classList.add('modal-open'); },
        destroy() { document.documentElement.classList.remove('modal-open'); }
    }"
    x-on:keydown.escape.window="$wire.set('{{ $model }}', false)"
    wire:click.self="$set('{{ $model }}', false)"
>
    <div
        x-on:click.stop
        class="umodal-box {{ $lg ? 'umodal-box-lg' : '' }}"
        @if($maxWidth) style="max-width:{{ $maxWidth }};" @endif
        role="dialog"
        aria-modal="true"
        aria-label="{{ $title ?? 'Dialog' }}"
    >
        @if($title)
            <div class="modal-hd">
                <h2 class="modal-title">{{ $title }}</h2>
                <button type="button" class="modal-close" wire:click="$set('{{ $model }}', false)" aria-label="Close">&times;</button>
            </div>
        @endif

        {{ $slot }}
    </div>
</div>
