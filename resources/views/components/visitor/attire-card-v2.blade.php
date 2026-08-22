@props(['attire', 'delay' => 0])

@php
    [$ad, $al] = \App\Support\PlaceholderPalette::visitor($attire->id);
    $genderKey = $attire->gender === 'women' ? 'bf' : 'bm';
@endphp

<article {{ $attributes->merge(['class' => 'dance-card-v2 anim-fade-up']) }}
         style="animation-delay:{{ $delay }}ms;"
         wire:click="selectAttire({{ $attire->id }})"
         @keydown.enter="$wire.selectAttire({{ $attire->id }})"
         @keydown.space.prevent="$wire.selectAttire({{ $attire->id }})"
         wire:key="att-{{ $attire->id }}"
         role="button" tabindex="0"
         aria-label="View {{ $attire->name_general }}">
    <div style="position:absolute;inset:0;background:linear-gradient(148deg,{{ $ad }} 0%,{{ $al }} 100%);"></div>
    @if($attire->image_path)
        @php $attireCardImg = Storage::disk('public')->url($attire->image_path); @endphp
        <img src="{{ $attireCardImg }}"
             alt="{{ $attire->name_general }}"
             loading="lazy"
             onerror="this.style.display='none'"
             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;" />
        <x-visitor.image-zoom-btn click="openPreview('{{ $attireCardImg }}')" />
    @endif
    <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(10,7,4,.6) 0%, transparent 55%);"></div>
    <div class="dc2-wm">{{ $attire->name_dialect ?: $attire->name_general }}</div>
    <div class="dc2-badge">
        <span class="vis-badge {{ $genderKey }}">{{ ucfirst($attire->gender) }}</span>
    </div>
    <div class="dc2-overlay">
        <h2 class="dc2-title">{{ $attire->name_general }}</h2>
        @if($attire->name_dialect)
            <p class="dc2-snip" style="font-style:normal;color:var(--gold-light);margin-bottom:.35rem;">{{ $attire->name_dialect }}</p>
        @endif
        <p class="dc2-snip">{{ $attire->description }}</p>
        <span class="dc2-link">
            <span x-show="!$store.app || $store.app.lang === 'en'">View Details →</span>
            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tingnan →</span>
        </span>
    </div>
</article>
