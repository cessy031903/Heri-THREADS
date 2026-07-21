<header class="hdr" role="banner" x-data="{ scrolled: false, mobileOpen: false }"
        :class="{ scrolled: scrolled }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)">

    <div class="hdr-inner">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="hdr-logo">
        Heri<span class="gem">◆</span>THREADS
    </a>

    {{-- Desktop Nav --}}
    <nav class="hdr-nav">
        <a href="{{ route('home') }}"
           class="nav-btn {{ request()->routeIs('home') ? 'active' : '' }}"
           {{ request()->routeIs('home') ? 'aria-current=page' : '' }}>
            <span x-show="!$store.app || $store.app.lang === 'en'">Home</span>
            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tahanan</span>
        </a>
        <a href="{{ route('dances') }}"
           class="nav-btn {{ request()->routeIs('dances') ? 'active' : '' }}"
           {{ request()->routeIs('dances') ? 'aria-current=page' : '' }}>
            <span x-show="!$store.app || $store.app.lang === 'en'">Explore Dances</span>
            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tuklasin ang Sayaw</span>
        </a>
        <a href="{{ route('attires') }}"
           class="nav-btn {{ request()->routeIs('attires') ? 'active' : '' }}"
           {{ request()->routeIs('attires') ? 'aria-current=page' : '' }}>
            <span x-show="!$store.app || $store.app.lang === 'en'">Explore Attires</span>
            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tuklasin ang Damit</span>
        </a>
    </nav>

    {{-- Admin link — only for authenticated admins --}}
    @auth
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="hdr-admin">Admin ↗</a>
        @endif
    @endauth

    {{-- Language Switcher --}}
    <div class="lang-sw">
        <button class="lang-btn" :class="(!$store.app || $store.app.lang === 'en') ? 'active' : ''"
                @click="$store.app && $store.app.setLang('en')">EN</button>
        <button class="lang-btn" :class="($store.app && $store.app.lang === 'fil') ? 'active' : ''"
                @click="$store.app && $store.app.setLang('fil')">FIL</button>
    </div>

    {{-- Hamburger (mobile only) --}}
    <button class="hdr-mob-btn" @click="mobileOpen = !mobileOpen"
            :class="{ open: mobileOpen }" aria-label="Toggle menu">
        <span></span><span></span><span></span>
    </button>

    </div>

    {{-- Mobile Dropdown (full width, below pill) --}}
    <div class="mob-nav" x-show="mobileOpen" x-cloak
         @click.outside="mobileOpen = false"
         x-transition>
        <a href="{{ route('home') }}" class="mob-nav-item {{ request()->routeIs('home') ? 'active' : '' }}"
           @click="mobileOpen = false">
            <span x-show="!$store.app || $store.app.lang === 'en'">Home</span>
            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tahanan</span>
        </a>
        <a href="{{ route('dances') }}" class="mob-nav-item {{ request()->routeIs('dances') ? 'active' : '' }}"
           @click="mobileOpen = false">
            <span x-show="!$store.app || $store.app.lang === 'en'">Explore Dances</span>
            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tuklasin ang Sayaw</span>
        </a>
        <a href="{{ route('attires') }}" class="mob-nav-item {{ request()->routeIs('attires') ? 'active' : '' }}"
           @click="mobileOpen = false">
            <span x-show="!$store.app || $store.app.lang === 'en'">Explore Attires</span>
            <span x-show="$store.app && $store.app.lang === 'fil'" x-cloak>Tuklasin ang Damit</span>
        </a>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="mob-nav-item" @click="mobileOpen = false">
                    Admin Panel ↗
                </a>
            @endif
        @endauth
    </div>

</header>
