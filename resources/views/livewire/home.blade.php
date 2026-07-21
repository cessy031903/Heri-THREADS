<div>
    {{-- ── SINGLE HERO ─────────────────────────────────────────────── --}}
    <section class="hero">

        {{-- Ambient background effects --}}
        <div class="hero-bg" aria-hidden="true">
            <div class="hero-orb hero-orb-1"></div>
            <div class="hero-orb hero-orb-2"></div>
            <div class="hero-grid"></div>
        </div>

        {{-- Text content --}}
        <div class="hero-body">
            <p class="hero-eye js-hero-eye">
                <span x-show="!$store.app || $store.app.lang === 'en'">Ifugao Traditional Dances and Attires Archive</span>
                <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Arkibo ng mga Tradisyunal na Sayaw at Kasuotan ng Ifugao</span>
            </p>

            <h1 class="hero-ttl js-hero-ttl">
                <span x-show="!$store.app || $store.app.lang === 'en'">
                    <em class="hero-hl">Sacred Dances,</em> Woven Attires<br>
                    <span class="hero-ttl-2">Preserved for Generations</span>
                </span>
                <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>
                    <em class="hero-hl">Mga Sayaw,</em> Inabel<br>
                    <span class="hero-ttl-2">Iningatan para sa Susunod</span>
                </span>
            </h1>

            <p class="hero-sub js-hero-sub">
                <span x-show="!$store.app || $store.app.lang === 'en'">A living digital archive of Ifugao traditional dances and woven attires — documented, digitized, and preserved for communities and future generations.</span>
                <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Isang buhay na digital na arkibo ng tradisyonal na mga sayaw at inabel ng Ifugao — para sa mga susunod na henerasyon.</span>
            </p>

            <a href="{{ route('dances') }}" class="hero-btn js-hero-btn">
                <span x-show="!$store.app || $store.app.lang === 'en'">Explore the Collection</span>
                <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tuklasin ang Koleksyon</span>
            </a>
        </div>

        {{-- Visual separation between the text and the gallery --}}
        <div class="hero-divider" aria-hidden="true"></div>

        {{-- Showcase gallery (5 fanned cards) ─────────────────────────
             Images come from the database (dances/attires with uploads).
             Missing images fall back to a themed gradient automatically.
        ──────────────────────────────────────────────────────────── --}}
        <div class="hero-gallery">
            @foreach($this->showcaseItems as $i => $item)
                <a href="{{ $item['href'] }}"
                   class="hg-card hg-card-{{ $i }} js-hg-card"
                   style="--hg-a: {{ $item['palette'][0] }}; --hg-b: {{ $item['palette'][1] }};">
                    @if($item['image'])
                        <img src="{{ $item['image'] }}" alt="{{ $item['label'] }}"
                             class="hg-img" loading="lazy"
                             onerror="this.style.display='none'">
                    @endif
                    <div class="hg-shade" aria-hidden="true"></div>
                    <div class="hg-text">
                        <span class="hg-sub">{{ $item['sub'] }}</span>
                        <span class="hg-label">{{ $item['label'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    @push('scripts')
    <script>
    (function () {
        function runHeroAnim() {
            if (typeof gsap === 'undefined') return;

            gsap.timeline({ defaults: { ease: 'power3.out' } })
                .from('.js-hero-eye', { y: 16, opacity: 0, duration: 0.6, delay: 0.05 })
                .from('.js-hero-ttl', { y: 30, opacity: 0, duration: 0.75 }, '-=0.35')
                .from('.js-hero-sub', { y: 20, opacity: 0, duration: 0.65 }, '-=0.45')
                .from('.js-hero-btn', { y: 14, opacity: 0, scale: 0.94,
                                        duration: 0.55, ease: 'back.out(1.7)' }, '-=0.4')
                .from('.js-hg-card', { y: 40, opacity: 0, duration: 0.7,
                                       stagger: 0.09, ease: 'power3.out' }, '-=0.25');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', runHeroAnim);
        } else {
            runHeroAnim();
        }
    })();
    </script>
    @endpush
</div>
