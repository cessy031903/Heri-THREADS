<div>
    @if(! $selectedMunicipality)
        {{-- ── MUNICIPALITY SELECTION ──────────────────────────── --}}
        <div class="vis-page-dark anim-fade-up" wire:key="mun-select"
             x-data="{
                previewUrl: null,
                openPreview(url) { this.previewUrl = url; },
                closePreview() { this.previewUrl = null; }
             }"
             @keydown.escape.window="previewUrl && closePreview()">
            <div class="vis-page-hd">
                <p class="vis-eyebrow">Ifugao Cultural Archive</p>
                <h1 class="vis-page-title">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Ifugao Attires</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Kasuotan ng Ifugao</span>
                </h1>
                <p class="vis-page-sub" style="margin-bottom:2.5rem;">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Each municipality preserves its own distinct textile language — passed down through generations of weavers</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Bawat munisipalidad ay nag-iingat ng sariling wika ng tela</span>
                </p>
            </div>

            <section class="dance-grid-v2" aria-label="Municipality selection">
                @foreach($this->municipalities as $i => $muni)
                    @php
                        $taglines = ['Alfonso Lista' => 'Gateway to Ifugao', 'Aguinaldo' => 'Where Traditions Breathe', 'Asipulo' => 'Land of the Mountain Springs', 'Banaue' => 'Home of the Eighth Wonder', 'Hingyon' => 'Village of the Weavers', 'Hungduan' => 'Heart of Highland Tradition', 'Kiangan' => 'Cradle of Ifugao Civilization', 'Lagawe' => 'Provincial Capital of Ifugao', 'Lamut' => 'Valley of Ancient Rites', 'Mayoyao' => 'Where Eagles Soar', 'Tinoc' => 'Land of the Tinoc Weavers'];
                        $tagline = $taglines[$muni] ?? 'Cultural Heritage Site';
                        $cardImage = $this->municipalityCardImages[$muni] ?? null;
                    @endphp
                    <x-visitor.municipality-card-v2
                        :muni="$muni"
                        :index="$i"
                        :tagline="$tagline"
                        :card-image="$cardImage" />
                @endforeach
            </section>

            {{-- Municipality card image lightbox --}}
            <x-visitor.photo-lightbox />
        </div>

    @else
        {{-- ── ATTIRE DETAIL (profile-style header) ─────────────── --}}
        @php
            $munIdx = array_search($selectedMunicipality, $this->municipalities, true);
            if ($munIdx === false) {
                $munIdx = 0;
            }
            [$covD, $covL] = \App\Support\PlaceholderPalette::municipality($munIdx);
            $taglines = ['Alfonso Lista' => 'Gateway to Ifugao', 'Aguinaldo' => 'Where Traditions Breathe', 'Asipulo' => 'Land of the Mountain Springs', 'Banaue' => 'Home of the Eighth Wonder', 'Hingyon' => 'Village of the Weavers', 'Hungduan' => 'Heart of Highland Tradition', 'Kiangan' => 'Cradle of Ifugao Civilization', 'Lagawe' => 'Provincial Capital of Ifugao', 'Lamut' => 'Valley of Ancient Rites', 'Mayoyao' => 'Where Eagles Soar', 'Tinoc' => 'Land of the Tinoc Weavers'];
            $munTagline = $taglines[$selectedMunicipality] ?? 'Cultural Heritage Site';
            $munInitials = mb_strtoupper(mb_substr(preg_replace('/\s+/u', '', $selectedMunicipality), 0, 2));
        @endphp

        <div class="mun-profile-page anim-fade-up" wire:key="mun-detail-{{ $selectedMunicipality }}"
             x-data="{
                previewUrl: null,
                openPreview(url) { this.previewUrl = url; },
                closePreview() { this.previewUrl = null; }
             }"
             @keydown.escape.window="previewUrl && closePreview()">
            <div class="mun-profile-cover" style="--cov-a: {{ $covD }}; --cov-b: {{ $covL }};">
                <div class="mun-profile-cover-bg" aria-hidden="true">
                    <svg class="mun-cover-svg" viewBox="0 0 80 80" preserveAspectRatio="xMidYMid slice">
                        <defs><pattern id="covpat" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><polygon points="10,1 19,10 10,19 1,10" fill="none" stroke="white" stroke-width="1.3" opacity="0.1"/><circle cx="10" cy="10" r="1.8" fill="white" opacity="0.07"/></pattern></defs>
                        <rect width="80" height="80" fill="url(#covpat)"/>
                    </svg>
                </div>
                <button type="button" class="mun-cover-back" wire:click="clearMunicipality()">
                    ←
                    <span x-show="!$store.app || $store.app.lang === 'en'">Back</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Bumalik</span>
                </button>
            </div>

            <div class="mun-profile-shell">
                <div class="mun-profile-bar">
                    <div class="mun-avatar" aria-hidden="true">{{ $munInitials }}</div>
                    <div class="mun-profile-text">
                        <p class="mun-profile-eyebrow">Ifugao Cultural Archive</p>
                        <h1 class="mun-profile-name">{{ $selectedMunicipality }}</h1>
                        <p class="mun-profile-tag">{{ $munTagline }}</p>
                    </div>
                </div>

                <div class="attires-wrap attires-wrap-profile">

                {{-- ── INTERACTIVE GUIDE (hotspots over an attire image) ── --}}
                {{-- Only render once the guide actually has hotspots, so empty
                     placeholder guides stay editable in admin without showing an
                     empty widget to visitors. --}}
                @if($this->guide && $this->guide->hotspots->isNotEmpty())
                    <section class="guide-section" x-data="{ active: null }">
                        <h2 class="guide-title">{{ $this->guide->title }}</h2>
                        <p class="guide-instruction">
                            {{ $this->guide->instruction ?: 'Hover or tap each number to explore' }}
                        </p>

                        <div class="paired-wrap guide-stage">
                            @if($this->guide->image_path)
                                <img src="{{ Storage::disk('public')->url($this->guide->image_path) }}"
                                     alt="{{ $this->guide->title }}"
                                     class="guide-img"
                                     loading="lazy"
                                     onerror="this.style.display='none'">
                            @else
                                <div class="guide-fallback" aria-hidden="true">
                                    <svg viewBox="0 0 80 80" preserveAspectRatio="xMidYMid slice">
                                        <defs><pattern id="guidepat" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                            <polygon points="10,1 19,10 10,19 1,10" fill="none" stroke="white" stroke-width="1.1" opacity="0.12"/>
                                            <circle cx="10" cy="10" r="1.6" fill="white" opacity="0.07"/>
                                        </pattern></defs>
                                        <rect width="80" height="80" fill="url(#guidepat)"/>
                                    </svg>
                                </div>
                            @endif

                            @foreach($this->guide->hotspots as $h)
                                <button type="button"
                                        class="hs-dot"
                                        style="left:{{ $h->pos_x }}%; top:{{ $h->pos_y }}%;"
                                        @mouseenter="active = {{ $h->id }}"
                                        @mouseleave="active = null"
                                        @click.stop="active = (active === {{ $h->id }} ? null : {{ $h->id }})"
                                        aria-label="{{ $h->label }}">
                                    <span class="hs-ring"></span>
                                    <span class="hs-inner">{{ $loop->iteration }}</span>
                                    <div class="hs-tooltip {{ $h->pos_y < 28 ? 'hs-tip-below' : 'hs-tip-above' }}"
                                         x-show="active === {{ $h->id }}"
                                         x-transition.opacity
                                         x-cloak>
                                        <p class="hs-tt-name">{{ $h->label }}</p>
                                        @if($h->description)
                                            <p class="hs-tt-desc">{{ $h->description }}</p>
                                        @endif
                                        @if($h->attire)
                                            <p class="hs-tt-link">◆ {{ $h->attire->name_general }}</p>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <div class="hs-legend">
                            @foreach($this->guide->hotspots as $h)
                                <span class="hs-legend-item">
                                    <span class="hs-legend-dot">{{ $loop->iteration }}</span>
                                    {{ $h->label }}
                                </span>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Women's Attire Section --}}
                <section class="attire-section">
                    <h2 class="sec-head">
                        <span x-show="!$store.app || $store.app.lang === 'en'">Women's Attire — {{ $selectedMunicipality }}</span>
                        <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Kasuotan ng Babae — {{ $selectedMunicipality }}</span>
                    </h2>
                    <div class="sec-line"></div>
                    <div class="dance-grid-v2 attire-grid-v2">
                    @forelse($this->womenAttires as $i => $attire)
                        <x-visitor.attire-card-v2 :attire="$attire" :delay="$i * 70" />
                    @empty
                        <div class="vis-empty-state" style="grid-column:1/-1;">
                            <span x-show="!$store.app || $store.app.lang === 'en'">No women's attires documented for this municipality yet.</span>
                            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Wala pang naitalang kasuotan ng babae para sa munisipalidad na ito.</span>
                        </div>
                    @endforelse
                </div>
                </section>

                {{-- Men's Attire Section --}}
                <section class="attire-section">
                    <h2 class="sec-head">
                        <span x-show="!$store.app || $store.app.lang === 'en'">Men's Attire — {{ $selectedMunicipality }}</span>
                        <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Kasuotan ng Lalaki — {{ $selectedMunicipality }}</span>
                    </h2>
                    <div class="sec-line"></div>
                    <div class="dance-grid-v2 attire-grid-v2">
                    @forelse($this->menAttires as $i => $attire)
                        <x-visitor.attire-card-v2 :attire="$attire" :delay="$i * 70" />
                    @empty
                        <div class="vis-empty-state" style="grid-column:1/-1;">
                            <span x-show="!$store.app || $store.app.lang === 'en'">No men's attires documented for this municipality yet.</span>
                            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Wala pang naitalang kasuotan ng lalaki para sa munisipalidad na ito.</span>
                        </div>
                    @endforelse
                </div>
                </section>

                </div>
            </div>

            <x-visitor.photo-lightbox />
        </div>
    @endif

    {{-- ── ATTIRE DETAIL MODAL (immersive) ──────────────────────── --}}
    @if($showAttireModal && $this->selectedAttire)
    @php
        $attire = $this->selectedAttire;
        [$amd, $aml] = \App\Support\PlaceholderPalette::visitor($attire->id);
        $genderKey = $attire->gender === 'women' ? 'bf' : 'bm';
        $aTagline = collect([$attire->name_dialect, $attire->municipality])->filter()->implode(' · ');
        $attireImgUrl = $attire->image_path ? Storage::disk('public')->url($attire->image_path) : null;
    @endphp
    <div class="vis-modal-ov" wire:click.self="closeAttireModal()"
         x-data="{
            previewUrl: null,
            init() { document.documentElement.classList.add('modal-open'); },
            destroy() { document.documentElement.classList.remove('modal-open'); },
            openPhoto() { this.previewUrl = @json($attireImgUrl); },
            closePreview() { this.previewUrl = null; }
         }"
         @keydown.escape.window="previewUrl ? closePreview() : null">
        <div class="dmodal" wire:key="amodal-{{ $attire->id }}">
            <x-visitor.modal-hero
                :image-url="$attireImgUrl"
                :image-alt="$attire->name_general"
                :gradient-from="$amd"
                :gradient-to="$aml"
                :pattern-id="'amp'.$attire->id">
                <x-slot:actions>
                    <button type="button" class="vis-close-btn" wire:click="closeAttireModal()" aria-label="Close">✕</button>
                </x-slot:actions>
                <div class="dmodal-hero-text">
                    <span class="vis-badge {{ $genderKey }}">{{ ucfirst($attire->gender) }}'s Attire</span>
                    <h2 class="dmodal-title">{{ $attire->name_general }}</h2>
                    @if($aTagline)
                        <p class="dmodal-tagline">{{ $aTagline }}</p>
                    @endif
                </div>
            </x-visitor.modal-hero>

            <div class="dmodal-scroll">
                {{-- DETAILS --}}
                <div class="dmodal-details">
                    <div class="dmodal-main">
                        <p class="dmodal-desc">{{ $attire->description }}</p>
                        @if($attire->cultural_significance)
                            <div class="dmodal-block">
                                <h3 class="dmodal-block-title">Cultural Significance</h3>
                                <p class="dmodal-block-text">{{ $attire->cultural_significance }}</p>
                            </div>
                        @endif
                    </div>
                    <aside class="dmodal-meta">
                        <div class="meta-card">
                            <span class="meta-label">Dialect Name</span>
                            <span class="meta-value">{{ $attire->name_dialect }}</span>
                        </div>
                        <div class="meta-card">
                            <span class="meta-label">Municipality</span>
                            <span class="meta-value">{{ $attire->municipality }}</span>
                        </div>
                        @if($attire->source_info)
                            <div class="meta-card">
                                <span class="meta-label">Source</span>
                                <span class="meta-value">{{ $attire->source_info }}</span>
                            </div>
                        @endif
                    </aside>
                </div>

                {{-- RELATED --}}
                @if($this->relatedAttires->isNotEmpty())
                    <div class="dmodal-section">
                        <p class="vis-modal-vid-label">◆ More from {{ $attire->municipality }}</p>
                        <div class="dmodal-related">
                            @foreach($this->relatedAttires as $rel)
                                @php [$rd, $rl] = \App\Support\PlaceholderPalette::visitor($rel->id); @endphp
                                <button type="button" class="rel-card" wire:click="selectAttire({{ $rel->id }})" wire:key="arel-{{ $rel->id }}">
                                    <span class="rel-thumb" style="background:linear-gradient(148deg,{{ $rd }},{{ $rl }});">
                                        @if($rel->image_path)
                                            <img src="{{ Storage::disk('public')->url($rel->image_path) }}" alt="{{ $rel->name_general }}" loading="lazy" onerror="this.style.display='none'" />
                                        @endif
                                    </span>
                                    <span class="rel-name">{{ $rel->name_general }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <x-visitor.photo-lightbox />
    </div>
    @endif
</div>
