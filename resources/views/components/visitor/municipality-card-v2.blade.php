@props(['muni', 'index' => 0, 'tagline' => '', 'cardImage' => null])

@php
    [$md, $ml] = \App\Support\PlaceholderPalette::municipality($index);
    $badgeColors = ['vb-pagaddut', 'vb-hinggatut', 'vb-dinuya'];
    $badgeKey = $badgeColors[$index % count($badgeColors)];
@endphp

<article {{ $attributes->merge(['class' => 'dance-card-v2 anim-fade-up']) }}
         style="animation-delay:{{ $index * 70 }}ms;"
         wire:click="selectMunicipality('{{ $muni }}')"
         @keydown.enter="$wire.selectMunicipality('{{ $muni }}')"
         @keydown.space.prevent="$wire.selectMunicipality('{{ $muni }}')"
         role="button" tabindex="0"
         aria-label="Explore {{ $muni }}">
    <div style="position:absolute;inset:0;background:linear-gradient(148deg,{{ $md }} 0%,{{ $ml }} 100%);">
        <svg style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none" viewBox="0 0 80 80" preserveAspectRatio="xMidYMid slice">
            <defs><pattern id="mpt{{ $index }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><polygon points="10,1 19,10 10,19 1,10" fill="none" stroke="white" stroke-width="1.3" opacity="0.09"/><circle cx="10" cy="10" r="1.8" fill="white" opacity="0.063"/><line x1="0" y1="10" x2="20" y2="10" stroke="white" stroke-width=".35" opacity="0.036"/><line x1="10" y1="0" x2="10" y2="20" stroke="white" stroke-width=".35" opacity="0.036"/></pattern></defs>
            <rect width="80" height="80" fill="url(#mpt{{ $index }})"/>
        </svg>
    </div>
    @if($cardImage)
        <img src="{{ $cardImage }}" alt="" aria-hidden="true"
             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"
             loading="lazy" onerror="this.style.display='none'">
        <x-visitor.image-zoom-btn click="openPreview('{{ $cardImage }}')" />
    @endif
    <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(10,7,4,.6) 0%, transparent 55%);"></div>
    <div class="dc2-wm">{{ $muni }}</div>
    <div class="dc2-badge">
        <span class="vis-badge {{ $badgeKey }}">Ifugao</span>
    </div>
    <div class="dc2-overlay">
        <h2 class="dc2-title">{{ $muni }}</h2>
        <p class="dc2-snip">{{ $tagline }}</p>
        <span class="dc2-link">
            <span x-show="!$store.app || $store.app.lang === 'en'">Explore Attire →</span>
            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tuklasin →</span>
        </span>
    </div>
</article>
