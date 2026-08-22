<div>

    {{-- ── TABLE CARD ──────────────────────────────────── --}}
    <div class="tbl-card afu">
        <div class="tbl-toolbar">
            <p style="font-size:.8rem;color:var(--gray);margin:0;flex:1;">
                Admin accounts that can sign in to this panel.
            </p>

            <span class="tbl-count">{{ $this->accounts->count() }} accounts</span>

            <button wire:click="openCreate" class="btn-admin btn-admin-primary btn-admin-sm">
                + Create New Account
            </button>
        </div>

        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->accounts as $account)
                        <tr wire:key="account-{{ $account->id }}">
                            <td>
                                <div class="td-name">
                                    {{ $account->name }}
                                    @if($account->id === auth()->id())
                                        <span style="font-size:.7rem;color:var(--gold);font-weight:600;">(you)</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span style="font-size:.82rem;color:var(--char);">{{ $account->email }}</span>
                            </td>
                            <td>
                                <div class="td-actions">
                                    <button wire:click="openEdit({{ $account->id }})"
                                            class="btn-icon" title="Edit" aria-label="Edit {{ $account->name }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if($account->id !== auth()->id())
                                        <button wire:click="delete({{ $account->id }})"
                                                wire:confirm="Delete '{{ $account->name }}'? This cannot be undone."
                                                class="btn-icon btn-icon-danger" title="Delete" aria-label="Delete {{ $account->name }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="3">No accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── MODAL ───────────────────────────────────────── --}}
    @if($showModal)
    <x-ui.modal model="showModal" :title="$isEditing ? 'Update Account' : 'Create New Account'">
            <div class="modal-body">
                <form wire:submit="save" id="account-form">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input wire:model="name" type="text" placeholder="e.g. Juan Dela Cruz"
                               class="form-input {{ $errors->has('name') ? 'error' : '' }}" />
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input wire:model="email" type="email" placeholder="admin@ifugao.local"
                               class="form-input {{ $errors->has('email') ? 'error' : '' }}" />
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ $isEditing ? 'New Password (optional)' : 'Password' }}</label>
                        <input wire:model="password" type="password" placeholder="••••••••"
                               class="form-input {{ $errors->has('password') ? 'error' : '' }}" />
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                        @if($isEditing)
                            <p style="font-size:.72rem;color:var(--gray-lt);margin-top:.25rem;">
                                Leave blank to keep the current password.
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password" placeholder="••••••••"
                               class="form-input" />
                    </div>
                </form>
            </div>
            <div class="modal-foot">
                <button type="button" wire:click="$set('showModal', false)" class="btn-admin btn-admin-outline">
                    Cancel
                </button>
                <button type="submit" form="account-form"
                        class="btn-admin btn-admin-primary"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Create Account' }}</span>
                    <span wire:loading>Saving…</span>
                </button>
            </div>
    </x-ui.modal>
    @endif

</div>
