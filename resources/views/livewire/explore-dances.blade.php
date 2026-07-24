<div>
    {{-- ── PAGE HEADER ────────────────────────────────────────── --}}
    <div class="vis-page-dark">
        <div class="vis-page-hd">
            <p class="vis-eyebrow">Ifugao Cultural Archive</p>
            <h1 class="vis-page-title">
                <span x-show="!$store.app || $store.app.lang === 'en'">Ifugao Dances</span>
                <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Mga Sayaw ng Ifugao</span>
            </h1>
            <p class="vis-page-sub">
                <span x-show="!$store.app || $store.app.lang === 'en'">Sacred movements and stories woven into every step of Ifugao tradition</span>
                <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Mga sagradong kilos at kuwentong nakatahi sa bawat hakbang ng tradisyong Ifugao</span>
            </p>
        </div>

        {{-- Search + Category Filter --}}
        <div style="max-width:1200px;margin:0 auto;padding:0 2rem 1.5rem;display:flex;flex-wrap:wrap;gap:.75rem;align-items:center;">
            {{-- Search --}}
            <div style="position:relative;flex:1;min-width:200px;max-width:320px;">
                <span style="position:absolute;left:.7rem;top:50%;transform:translateY(-50%);pointer-events:none;color:rgba(255,255,255,.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                </span>
                <input wire:model.live.debounce.300ms="search"
                       type="search"
                       placeholder="Search dances…"
                       style="width:100%;padding:.5rem .875rem .5rem 2.25rem;background:rgba(255,255,255,.07);border:1.5px solid rgba(255,255,255,.1);border-radius:.5rem;color:rgba(255,255,255,.85);font-family:var(--font-body);font-size:.8125rem;" />
            </div>
            {{-- Category pills --}}
            <div class="cat-filter" style="padding:0;margin:0;" role="group" aria-label="Filter by category">
                @foreach($categories as $cat)
                    <button wire:click="$set('categoryFilter', '{{ $cat['id'] }}')"
                            class="cat-pill {{ $categoryFilter === $cat['id'] ? 'active' : '' }}">
                        {{ $cat['name'] }}
                    </button>
                @endforeach
                @if($this->dances->total() > 0)
                    <span style="font-family:var(--font-body);font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.18);align-self:center;padding-left:.5rem;" wire:loading.class="opacity-0">
                        {{ $this->dances->total() }} {{ $this->dances->total() === 1 ? 'dance' : 'dances' }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Skeleton grid (shown while searching/filtering) --}}
        <div class="dance-grid-v2 dance-skel-grid" wire:loading.delay.class="skel-on" wire:target="search,categoryFilter">
            @for($s = 0; $s < 8; $s++)
                <div class="dance-skel"></div>
            @endfor
        </div>

        {{-- Dance Cards V2 --}}
        <section class="dance-grid-v2" wire:loading.delay.remove wire:target="search,categoryFilter">
            @forelse($this->dances as $i => $dance)
                @php
                    $pals = [['#7B3A10','#C4854A'],['#5C1F1F','#C85A17'],['#1A3A10','#3A7A24'],['#3A2A10','#A0824D'],['#1A2A4A','#3A6A95'],['#4A1A2A','#A84060'],['#2A3A10','#7A9A3A'],['#3A1A10','#B07040']];
                    $palIndex = abs((int) $dance->id) % count($pals);
                    [$d, $l] = $pals[$palIndex];
                    $delay = $loop->index * 70;
                    $catColors = ['vb-pagaddut', 'vb-hinggatut', 'vb-dinuya'];
                    $catKey = $catColors[abs(crc32((string) $dance->category)) % count($catColors)];
                @endphp
                <article class="dance-card-v2 anim-fade-up"
                         style="animation-delay:{{ $delay }}ms;"
                         wire:click="selectDance({{ $dance->id }})"
                         role="button" tabindex="0"
                         aria-label="Explore {{ $dance->name }}">
                    {{-- Gradient background --}}
                    <div style="position:absolute;inset:0;background:linear-gradient(148deg,{{ $d }} 0%,{{ $l }} 100%);">
                        <svg style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none" viewBox="0 0 80 80" preserveAspectRatio="xMidYMid slice">
                            <defs><pattern id="pt{{ $dance->id }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><polygon points="10,1 19,10 10,19 1,10" fill="none" stroke="white" stroke-width="1.3" opacity="0.09"/><circle cx="10" cy="10" r="1.8" fill="white" opacity="0.063"/><line x1="0" y1="10" x2="20" y2="10" stroke="white" stroke-width=".35" opacity="0.036"/><line x1="10" y1="0" x2="10" y2="20" stroke="white" stroke-width=".35" opacity="0.036"/></pattern></defs>
                            <rect width="80" height="80" fill="url(#pt{{ $dance->id }})"/>
                        </svg>
                    </div>
                    {{-- Image overlay if exists --}}
                    @if($dance->image_path)
                        <img src="{{ Storage::disk('public')->url($dance->image_path) }}"
                             alt="{{ $dance->name }}"
                             onerror="this.style.display='none'"
                             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;" />
                    @endif
                    {{-- Vignette --}}
                    <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(10,7,4,.6) 0%, transparent 55%);"></div>
                    {{-- Watermark --}}
                    <div class="dc2-wm">{{ $dance->name }}</div>
                    {{-- Badge --}}
                    <div class="dc2-badge">
                        <span class="vis-badge {{ $catKey }}">{{ $dance->category }}</span>
                    </div>
                    {{-- Hover overlay --}}
                    <div class="dc2-overlay">
                        <h2 class="dc2-title">{{ $dance->name }}</h2>
                        <p class="dc2-snip">{{ $dance->description }}</p>
                        <span class="dc2-link">
                            <span x-show="!$store.app || $store.app.lang === 'en'">View Details →</span>
                            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tingnan →</span>
                        </span>
                    </div>
                </article>
            @empty
                <div style="grid-column:1/-1; text-align:center; padding:5rem 1.5rem;">
                    <p style="font-family:var(--font-body); font-size:1rem; color:rgba(255,255,255,.3); font-style:italic;">No dances found.</p>
                </div>
            @endforelse
        </section>

        {{-- Pagination --}}
        @if($this->dances->hasPages())
            <div style="display:flex;justify-content:center;padding:1.5rem 2rem 4rem;">
                {{ $this->dances->links() }}
            </div>
        @endif
    </div>

    {{-- ── DANCE DETAIL MODAL (immersive) ──────────────────────── --}}
    @if($showModal && $this->selectedDance)
    @php
        $dance = $this->selectedDance;
        $pals = [['#7B3A10','#C4854A'],['#5C1F1F','#C85A17'],['#1A3A10','#3A7A24'],['#3A2A10','#A0824D'],['#1A2A4A','#3A6A95'],['#4A1A2A','#A84060'],['#2A3A10','#7A9A3A'],['#3A1A10','#B07040']];
        $palIndex = abs((int) $dance->id + 10) % count($pals);
        [$md, $ml] = $pals[$palIndex];
        $mCatColors = ['vb-pagaddut', 'vb-hinggatut', 'vb-dinuya'];
        $mCatKey = $mCatColors[abs(crc32((string) $dance->category)) % count($mCatColors)];
        $tagline = collect([$dance->region, $dance->origin])->filter()->implode(' · ');
    @endphp
    <div class="vis-modal-ov" wire:click.self="closeModal()"
         x-data="{ init() { document.documentElement.classList.add('modal-open'); }, destroy() { document.documentElement.classList.remove('modal-open'); } }">
        <div class="dmodal" wire:key="dmodal-{{ $dance->id }}">
            <button class="vis-close-btn" wire:click="closeModal()" aria-label="Close">✕</button>

            {{-- SECTION 1 — HERO --}}
            <header class="dmodal-hero">
                <div class="dmodal-hero-bg" style="background:linear-gradient(148deg,{{ $md }} 0%,{{ $ml }} 100%);">
                    <svg style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none" viewBox="0 0 80 80" preserveAspectRatio="xMidYMid slice">
                        <defs><pattern id="ptm{{ $dance->id }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><polygon points="10,1 19,10 10,19 1,10" fill="none" stroke="white" stroke-width="1.3" opacity="0.09"/><circle cx="10" cy="10" r="1.8" fill="white" opacity="0.063"/></pattern></defs>
                        <rect width="80" height="80" fill="url(#ptm{{ $dance->id }})"/>
                    </svg>
                </div>
                @if($dance->image_path)
                    <img src="{{ Storage::disk('public')->url($dance->image_path) }}"
                         alt="{{ $dance->name }}"
                         onerror="this.style.display='none'"
                         class="dmodal-hero-img" />
                @endif
                <div class="dmodal-hero-shade"></div>
                <div class="dmodal-hero-text">
                    <span class="vis-badge {{ $mCatKey }}">{{ $dance->category }}</span>
                    <h2 class="dmodal-title">{{ $dance->name }}</h2>
                    @if($tagline)
                        <p class="dmodal-tagline">{{ $tagline }}</p>
                    @endif
                </div>
            </header>

            {{-- SCROLLABLE BODY --}}
            <div class="dmodal-scroll">
                {{-- SECTION 2 — DETAILS --}}
                <div class="dmodal-details">
                    <div class="dmodal-main">
                        <p class="dmodal-desc">{{ $dance->description }}</p>

                        @if($dance->cultural_meaning)
                            <div class="dmodal-block">
                                <h3 class="dmodal-block-title">Cultural Meaning</h3>
                                <p class="dmodal-block-text">{{ $dance->cultural_meaning }}</p>
                            </div>
                        @endif
                        @if($dance->historical_background)
                            <div class="dmodal-block">
                                <h3 class="dmodal-block-title">Historical Background</h3>
                                <p class="dmodal-block-text">{{ $dance->historical_background }}</p>
                            </div>
                        @endif
                    </div>

                    <aside class="dmodal-meta">
                        <div class="meta-card">
                            <span class="meta-label">Category</span>
                            <span class="meta-value">{{ ucfirst($dance->category) }}</span>
                        </div>
                        @if($dance->region)
                            <div class="meta-card">
                                <span class="meta-label">Region</span>
                                <span class="meta-value">{{ $dance->region }}</span>
                            </div>
                        @endif
                        @if($dance->origin)
                            <div class="meta-card">
                                <span class="meta-label">Origin</span>
                                <span class="meta-value">{{ $dance->origin }}</span>
                            </div>
                        @endif
                    </aside>
                </div>

                {{-- SECTION 3 — VIDEO --}}
                <div class="dmodal-section">
                    <p class="vis-modal-vid-label">◆
                        <span x-show="!$store.app || $store.app.lang === 'en'">Watch Performance</span>
                        <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Panoorin ang Pagtatanghal</span>
                    </p>
                    @if($dance->video_path)
                        <div class="vid-wrap">
                            <video src="{{ Storage::disk('public')->url($dance->video_path) }}"
                                   controls
                                   preload="metadata"
                                   style="width:100%;height:100%;"></video>
                        </div>
                    @elseif($dance->embed_url)
                        <div class="vid-wrap">
                            <iframe src="{{ $dance->embed_url }}"
                                    title="{{ $dance->name }}"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="vid-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>No performance video available yet.</span>
                        </div>
                    @endif
                </div>

                {{-- SECTION 4 — RELATED --}}
                @if($this->relatedDances->isNotEmpty())
                    <div class="dmodal-section">
                        <p class="vis-modal-vid-label">◆ Related Dances</p>
                        <div class="dmodal-related">
                            @foreach($this->relatedDances as $rel)
                                @php $relPalIndex = abs((int) $rel->id) % count($pals); [$rd, $rl] = $pals[$relPalIndex]; @endphp
                                <button type="button" class="rel-card" wire:click="selectDance({{ $rel->id }})" wire:key="rel-{{ $rel->id }}">
                                    <span class="rel-thumb" style="background:linear-gradient(148deg,{{ $rd }},{{ $rl }});">
                                        @if($rel->image_path)
                                            <img src="{{ Storage::disk('public')->url($rel->image_path) }}" alt="{{ $rel->name }}" loading="lazy" onerror="this.style.display='none'" />
                                        @endif
                                    </span>
                                    <span class="rel-name">{{ $rel->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
