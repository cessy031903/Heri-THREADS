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
                    A living digital archive preserving the traditional dances and woven attires
                    of Ifugao's eleven municipalities for generations to come.
                </p>
            </div>

            <div class="sf-col">
                <p class="sf-col-title">Explore</p>
                <a href="{{ route('home') }}" class="sf-link">Home</a>
                <a href="{{ route('dances') }}" class="sf-link">Dances</a>
                <a href="{{ route('attires') }}" class="sf-link">Attires</a>
            </div>

            <div class="sf-col">
                <p class="sf-col-title">Archive</p>
                <a href="{{ route('attires') }}" class="sf-link">Municipalities</a>
                <a href="{{ route('dances') }}" class="sf-link">Sacred Dances</a>
                <a href="{{ route('login') }}" class="sf-link">Admin Access</a>
            </div>

            <div class="sf-col sf-col-wide">
                <p class="sf-col-title">Stay connected</p>
                <p class="sf-note">Heritage notes and new additions to the archive.</p>
                <form class="sf-news" onsubmit="return false;">
                    <input type="email" class="sf-news-input" placeholder="Your email" aria-label="Email address">
                    <button type="submit" class="sf-news-btn">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="sf-bottom">
            <span>&copy; {{ date('Y') }} Heri◆THREADS — Ifugao Cultural Archive</span>
            <span class="sf-bottom-note">For educational and cultural preservation purposes.</span>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"
            integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt"
            crossorigin="anonymous" defer></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
