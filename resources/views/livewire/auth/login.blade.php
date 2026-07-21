<div class="auth-bg">
    <div class="auth-card">

        <div style="font-family:var(--font-display); font-size:1.25rem; font-weight:700; color:var(--char); display:flex; align-items:center; gap:.35rem; margin-bottom:.375rem;">
            Heri<span style="color:var(--gold);">◆</span>THREADS
        </div>
        <div style="font-family:var(--font-body); font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--gold); margin-bottom:2rem;">
            Admin Portal
        </div>

        <h1 style="font-family:var(--font-display); font-size:1.625rem; font-weight:700; color:var(--char); margin-bottom:.375rem;">
            Welcome back
        </h1>
        <p style="font-family:var(--font-body); font-size:.875rem; color:var(--gray); margin-bottom:1.75rem;">
            Sign in to manage the cultural archive
        </p>

        <form wire:submit="submit" novalidate>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input wire:model="email"
                       type="email"
                       placeholder="admin@ifugao.local"
                       autocomplete="email"
                       class="form-input {{ $errors->has('email') ? 'error' : '' }}" />
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input wire:model="password"
                       type="password"
                       placeholder="••••••••"
                       autocomplete="current-password"
                       class="form-input {{ $errors->has('password') ? 'error' : '' }}" />
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex; align-items:center; gap:.5rem; margin-bottom:1.125rem;">
                <input wire:model="remember" type="checkbox" id="remember"
                       style="width:14px; height:14px; accent-color:var(--gold); cursor:pointer;" />
                <label for="remember" style="font-size:.8125rem; color:var(--gray); cursor:pointer;">
                    Keep me signed in
                </label>
            </div>

            <button type="submit"
                    class="btn-admin btn-admin-primary btn-admin-full"
                    wire:loading.attr="disabled"
                    style="margin-top:.25rem;">
                <span wire:loading.remove>Sign In</span>
                <span wire:loading style="display:none; align-items:center; gap:.5rem;">
                    <svg style="animation:spin 1s linear infinite; width:14px; height:14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity:.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path style="opacity:.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Signing in…
                </span>
            </button>
        </form>

        <p style="margin-top:1.5rem; font-size:.72rem; color:var(--gray-lt); text-align:center;">
            Ifugao Cultural Archive &copy; {{ date('Y') }}
        </p>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</div>
