<div class="auth-bg">
    <div class="auth-card">

        <div style="font-family:var(--font-display); font-size:1.25rem; font-weight:700; color:var(--char); display:flex; align-items:center; gap:.35rem; margin-bottom:.375rem;">
            Heri<span style="color:var(--gold);">◆</span>THREADS
        </div>
        <div style="font-family:var(--font-body); font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--gold); margin-bottom:2rem;">
            Admin Portal
        </div>

        @if($sent)
            <h1 style="font-family:var(--font-display); font-size:1.625rem; font-weight:700; color:var(--char); margin-bottom:.375rem;">
                Check your email
            </h1>
            <p style="font-family:var(--font-body); font-size:.875rem; color:var(--gray); margin-bottom:1.75rem; line-height:1.6;">
                If an account exists for <strong>{{ $email }}</strong>, we've sent a link to reset your password.
            </p>

            <a href="{{ route('login') }}" wire:navigate
               class="btn-admin btn-admin-primary btn-admin-full"
               style="text-align:center; display:block; text-decoration:none;">
                Back to Sign In
            </a>
        @else
            <h1 style="font-family:var(--font-display); font-size:1.625rem; font-weight:700; color:var(--char); margin-bottom:.375rem;">
                Forgot your password?
            </h1>
            <p style="font-family:var(--font-body); font-size:.875rem; color:var(--gray); margin-bottom:1.75rem;">
                Enter your email and we'll send you a reset link
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

                <button type="submit"
                        class="btn-admin btn-admin-primary btn-admin-full"
                        wire:loading.attr="disabled"
                        style="margin-top:.25rem;">
                    <span wire:loading.remove>Send Reset Link</span>
                    <span wire:loading style="display:none; align-items:center; gap:.5rem;">
                        <svg style="animation:spin 1s linear infinite; width:14px; height:14px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity:.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity:.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Sending…
                    </span>
                </button>
            </form>

            <p style="margin-top:1.25rem; font-size:.8125rem; text-align:center;">
                <a href="{{ route('login') }}" wire:navigate style="color:var(--gold); text-decoration:none;">
                    ← Back to Sign In
                </a>
            </p>
        @endif

        <p style="margin-top:1.5rem; font-size:.72rem; color:var(--gray-lt); text-align:center;">
            Ifugao Cultural Archive &copy; {{ date('Y') }}
        </p>
    </div>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</div>
