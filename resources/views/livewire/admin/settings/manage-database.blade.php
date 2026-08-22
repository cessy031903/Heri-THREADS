<div>

    {{-- ── BACKUP ──────────────────────────────────────── --}}
    <div class="tbl-card afu" style="padding:1.5rem;margin-bottom:1.5rem;">
        <h2 style="font-family:var(--font-display);font-size:1.1rem;margin:0 0 .375rem;">Backup</h2>
        <p style="font-size:.85rem;color:var(--gray);margin:0 0 1rem;">
            Download a full export of all dances, attires, guides, showcase photos, and accounts as a single JSON file.
            Keep this somewhere safe — it's the fastest way to recover if something goes wrong.
        </p>
        <button wire:click="backup" class="btn-admin btn-admin-primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="backup">Download Backup</span>
            <span wire:loading wire:target="backup">Preparing…</span>
        </button>
    </div>

    {{-- ── RESTORE ─────────────────────────────────────── --}}
    <div class="tbl-card afu" style="padding:1.5rem;">
        <h2 style="font-family:var(--font-display);font-size:1.1rem;margin:0 0 .375rem;">Restore</h2>
        <p style="font-size:.85rem;color:var(--red);margin:0 0 1rem;font-weight:600;">
            ⚠ This replaces all current dances, attires, guides, showcase photos, and accounts with the contents
            of the uploaded file. This cannot be undone from the app — only from a backup file.
            Your own account (the one you're signed in as) is kept either way, so you won't be locked out.
        </p>

        @if(! $confirmingRestore)
            <form wire:submit="stageRestore">
                <div class="form-group">
                    <label class="form-label">Backup File (.json)</label>
                    <input wire:model="restoreFile" type="file" accept="application/json,.json" class="form-input {{ $errors->has('restoreFile') ? 'error' : '' }}" />
                    @error('restoreFile') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-admin btn-admin-outline" style="border-color:var(--red);color:var(--red);" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="stageRestore">Choose File to Restore</span>
                    <span wire:loading wire:target="stageRestore">Checking…</span>
                </button>
            </form>
        @else
            <div style="background:rgba(220,38,38,.06);border:1px solid rgba(220,38,38,.25);border-radius:.5rem;padding:1rem;">
                <p style="font-size:.85rem;font-weight:600;color:var(--red);margin:0 0 .5rem;">
                    Restoring from: {{ $pendingRestoreFilename }}
                </p>
                <p style="font-size:.8rem;color:var(--char);margin:0 0 1rem;">
                    A safety copy of the current database will be saved automatically before this happens.
                    Are you sure you want to proceed?
                </p>
                <div style="display:flex;gap:.5rem;">
                    <button wire:click="cancelRestore" class="btn-admin btn-admin-outline">Cancel</button>
                    <button wire:click="confirmRestore" wire:confirm="This will overwrite all current data. Continue?"
                            class="btn-admin" style="background:var(--red);color:#fff;"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="confirmRestore">Yes, Restore Now</span>
                        <span wire:loading wire:target="confirmRestore">Restoring…</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

</div>
