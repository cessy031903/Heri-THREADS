<!DOCTYPE html>
<html lang="en" data-theme="light" x-data>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Heri-THREADS — Ifugao Cultural Archive' }}</title>
    <meta name="description" content="{{ $description ?? 'Heri-THREADS — A digital preservation of Ifugao traditional dances and woven attires from the 11 municipalities of Ifugao province, Philippines.' }}">
    <meta property="og:title" content="{{ $title ?? 'Heri-THREADS — Ifugao Cultural Archive' }}">
    <meta property="og:description" content="{{ $description ?? 'A digital preservation of Ifugao traditional dances and woven attires from the Cordillera highlands of the Philippines.' }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_PH">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#5D7052">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600..800;1,9..144,600&family=Nunito:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="canonical" href="{{ url()->current() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="heri-surface" style="-webkit-font-smoothing:antialiased; overflow-x:hidden;">

    @include('components.header')

    <main>
        {{ $slot }}
    </main>

    <footer class="site-footer">
        <div class="sf-inner">
            <div class="sf-brand">
                <a href="{{ route('home') }}" class="sf-logo">Heri<span class="gem">◆</span>THREADS</a>
                <p class="sf-blurb">
                    <span x-show="!$store.app || $store.app.lang === 'en'">A living digital archive preserving the traditional dances and woven attires
                    of Ifugao's eleven municipalities for generations to come.</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Isang buhay na digital na arkibo na nag-iingat ng mga tradisyonal na sayaw
                    at hinabing kasuotan ng labing-isang munisipalidad ng Ifugao para sa susunod na salinlahi.</span>
                </p>
            </div>

            <div class="sf-col">
                <p class="sf-col-title">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Explore</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tuklasin</span>
                </p>
                <a href="{{ route('home') }}" class="sf-link">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Home</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tahanan</span>
                </a>
                <a href="{{ route('dances') }}" class="sf-link">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Dances</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Mga Sayaw</span>
                </a>
                <a href="{{ route('attires') }}" class="sf-link">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Attires</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Mga Kasuotan</span>
                </a>
            </div>

            <div class="sf-col">
                <p class="sf-col-title">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Archive</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Arkibo</span>
                </p>
                <a href="{{ route('attires') }}" class="sf-link">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Municipalities</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Mga Munisipalidad</span>
                </a>
                <a href="{{ route('dances') }}" class="sf-link">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Sacred Dances</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Mga Sagradong Sayaw</span>
                </a>
                <a href="{{ route('login') }}" class="sf-link">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Admin Access</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Admin Access</span>
                </a>
            </div>

            <div class="sf-col sf-col-wide">
                <p class="sf-col-title">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Stay connected</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Manatiling Nakakonekta</span>
                </p>
                <p class="sf-note">
                    <span x-show="!$store.app || $store.app.lang === 'en'">Heritage notes and new additions to the archive.</span>
                    <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Mga tala ng pamana at bagong idinagdag sa arkibo.</span>
                </p>
                <form class="sf-news" onsubmit="return false;">
                    <input type="email" class="sf-news-input" aria-label="Email address"
                           :placeholder="(!$store.app || $store.app.lang === 'en') ? 'Your email' : 'Iyong email'">
                    <button type="submit" class="sf-news-btn">
                        <span x-show="!$store.app || $store.app.lang === 'en'">Subscribe</span>
                        <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Mag-subscribe</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="sf-bottom">
            <span>&copy; {{ date('Y') }} Heri◆THREADS — Ifugao Cultural Archive</span>
            <span class="sf-bottom-note">
                <span x-show="!$store.app || $store.app.lang === 'en'">For educational and cultural preservation purposes.</span>
                <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Para sa layuning pang-edukasyon at preserbasyong pangkultura.</span>
            </span>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"
            integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt"
            crossorigin="anonymous" defer></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
