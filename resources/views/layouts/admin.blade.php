<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} — Heri-THREADS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts
</head>
<body style="background:var(--cream); height:100vh; overflow:hidden; font-family:var(--font-admin);">

<div class="admin-shell" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div class="admin-mob-overlay"
         x-show="sidebarOpen"
         x-cloak
         @click="sidebarOpen = false"
         style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:199;">
    </div>

    {{-- ── SIDEBAR ─────────────────────────────────────── --}}
    <aside class="admin-sidebar" :class="{ 'admin-sidebar-open': sidebarOpen }">
        <div class="sb-logo">
            <div class="sb-logo-text">
                Heri<span class="gem">◆</span>THREADS
            </div>
            <div class="sb-logo-sub">Admin Panel</div>
        </div>

        <nav class="sb-nav">
            <div class="sb-section">Main</div>

            <a href="{{ route('admin.dashboard') }}" wire:navigate
               class="sb-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <span class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </span>
                Dashboard
            </a>

            <div class="sb-section" style="margin-top:.875rem;">Content</div>

            <a href="{{ route('admin.dances') }}" wire:navigate
               class="sb-item {{ request()->routeIs('admin.dances') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <span class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                </span>
                Manage Dances
            </a>

            <a href="{{ route('admin.attires') }}" wire:navigate
               class="sb-item {{ request()->routeIs('admin.attires') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <span class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </span>
                Manage Attires
            </a>

            <a href="{{ route('admin.guides') }}" wire:navigate
               class="sb-item {{ request()->routeIs('admin.guides') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <span class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </span>
                Interactive Guides
            </a>

            <div class="sb-section" style="margin-top:.875rem;">Site</div>

            <a href="{{ route('dances') }}" target="_blank" class="sb-item" @click="sidebarOpen = false">
                <span class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </span>
                View Public Site
            </a>
        </nav>

        <div class="sb-footer">
            <div class="sb-user">
                <div class="sb-avatar">
                    {{ strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="sb-user-info">
                    <div class="sb-user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="sb-user-role">Administrator</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display:contents;">
                    @csrf
                    <button type="submit" class="sb-logout" title="Sign out">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── MAIN ────────────────────────────────────────── --}}
    <div class="admin-content">

        {{-- Topbar --}}
        <div class="admin-topbar">
            {{-- Mobile hamburger --}}
            <button class="admin-mob-btn" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar">
                <span :class="sidebarOpen ? 'admin-mob-line-open' : ''"></span>
                <span :class="sidebarOpen ? 'admin-mob-line-open' : ''"></span>
                <span :class="sidebarOpen ? 'admin-mob-line-open' : ''"></span>
            </button>
            <h1 class="topbar-title">{{ $title ?? 'Dashboard' }}</h1>
        </div>

        {{-- Page content --}}
        <div class="admin-main">
            {{ $slot }}
        </div>

    </div>
</div>

{{-- Toast notification --}}
<div id="admin-toast-container"
     style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;pointer-events:none;">
</div>

@livewireScripts
<script>
    window.addEventListener('toast', (e) => {
        const { message, type } = e.detail[0] ?? e.detail;
        const el = document.createElement('div');
        el.className = `admin-toast admin-toast-${type ?? 'success'}`;
        el.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/></svg> ${message}`;
        document.getElementById('admin-toast-container').appendChild(el);
        setTimeout(() => el.remove(), 3500);
    });
</script>
</body>
</html>
