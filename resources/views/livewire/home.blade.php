<div>
    {{-- ── SINGLE HERO ─────────────────────────────────────────────── --}}
    <section class="hero">

        {{-- Ambient background effects --}}
        <div class="hero-bg" aria-hidden="true"
             x-data="{
                slides: [
                    '{{ asset('images/hero-banaue.avif') }}',
                    '{{ asset('images/hero-batad-2.jpg') }}',
                    '{{ asset('images/hero-batad-3.webp') }}',
                ],
                active: 0,
                init() {
                    setInterval(() => { this.active = (this.active + 1) % this.slides.length; }, 6000);
                }
             }">
            <template x-for="(slide, i) in slides" :key="i">
                <div class="hero-photo"
                     :style="'background-image:url(\'' + slide + '\');'"
                     :class="{ 'hero-photo-active': active === i }"></div>
            </template>
            <div class="hero-photo-shade"></div>
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

        {{-- Showcase carousel ────────────────────────────────────────
             Images come from the database (dances/attires with uploads).
             Missing images fall back to a themed gradient automatically.
             Auto-advances right-to-left; centered card is highlighted;
             supports swipe/drag and prev/next arrows.
        ──────────────────────────────────────────────────────────── --}}
        <div class="hero-carousel"
             x-data="heroCarousel({{ count($this->showcaseItems) }})"
             x-init="init()"
             @mouseenter="pause()" @mouseleave="resume()"
             @keydown.left="prev()" @keydown.right="next()"
             tabindex="0"
             role="region"
             aria-roledescription="carousel"
             aria-label="Featured dances and attires">

            <button type="button" class="hc-arrow hc-arrow-prev" @click="prev()" aria-label="Previous">‹</button>

            <div class="hc-track"
                 @pointerdown="dragStart($event)"
                 @pointermove="dragMove($event)"
                 @pointerup="dragEnd($event)"
                 @pointercancel="dragEnd($event)">
                @foreach($this->showcaseItems as $i => $item)
                    <a href="{{ $item['href'] }}"
                       class="hg-card js-hg-card"
                       :class="cardClass({{ $i }})"
                       :style="cardStyle({{ $i }})"
                       style="--hg-a: {{ $item['palette'][0] }}; --hg-b: {{ $item['palette'][1] }};"
                       @click.prevent="onCardClick({{ $i }}, $event)">
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

            <button type="button" class="hc-arrow hc-arrow-next" @click="next()" aria-label="Next">›</button>

            <div class="hc-dots" role="tablist" aria-label="Slide position">
                @foreach($this->showcaseItems as $i => $item)
                    <button type="button" class="hc-dot" :class="{ 'hc-dot-active': isCenter({{ $i }}) }"
                            @click="goTo({{ $i }})" aria-label="Go to slide {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
    function heroCarousel(count) {
        return {
            count: count,
            center: 0,
            timer: null,
            paused: false,
            dragX: null,
            dragging: false,

            init() {
                this.play();
            },
            play() {
                clearInterval(this.timer);
                this.timer = setInterval(() => {
                    if (! this.paused) this.next();
                }, 4000);
            },
            pause() { this.paused = true; },
            resume() { this.paused = false; },
            pauseThenResume() {
                this.pause();
                clearTimeout(this._resumeT);
                this._resumeT = setTimeout(() => this.resume(), 5000);
            },
            next() { this.center = (this.center + 1) % this.count; },
            prev() { this.center = (this.center - 1 + this.count) % this.count; },
            goTo(i) { this.center = i; this.pauseThenResume(); },
            onCardClick(i, event) {
                if (this.isCenter(i)) {
                    window.location.href = event.currentTarget.href;
                } else {
                    this.goTo(i);
                }
            },
            isCenter(i) { return i === this.center; },

            // Signed shortest circular distance from center, e.g. -2..-1,0,1..2
            offsetOf(i) {
                const half = this.count / 2;
                let d = i - this.center;
                if (d > half) d -= this.count;
                if (d < -half) d += this.count;
                return d;
            },
            cardClass(i) {
                const d = this.offsetOf(i);
                return {
                    'hg-card-center': d === 0,
                    'hg-card-hidden': Math.abs(d) > 2,
                };
            },
            cardStyle(i) {
                const d = this.offsetOf(i);
                if (Math.abs(d) > 2) {
                    return 'opacity:0; pointer-events:none; transform:translateX(0) scale(.7);';
                }
                const spacing = window.innerWidth < 560 ? 92 : (window.innerWidth < 760 ? 128 : 168);
                const x = d * spacing;
                const scale = d === 0 ? 1.08 : 1 - Math.abs(d) * 0.12;
                const rotate = d * 6;
                const y = Math.abs(d) * 20;
                const z = 10 - Math.abs(d);
                return `transform: translateX(${x}px) translateY(${y}px) scale(${scale}) rotate(${rotate}deg); z-index:${z};`;
            },

            dragStart(e) {
                this.dragging = true;
                this.dragX = e.clientX;
                this.pause();
            },
            dragMove(e) {
                if (! this.dragging || this.dragX === null) return;
            },
            dragEnd(e) {
                if (! this.dragging || this.dragX === null) { this.dragging = false; return; }
                const delta = e.clientX - this.dragX;
                if (delta > 40) this.prev();
                else if (delta < -40) this.next();
                this.dragging = false;
                this.dragX = null;
                this.pauseThenResume();
            },
        };
    }
    </script>
    @endpush

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
