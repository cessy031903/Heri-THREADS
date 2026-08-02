<div>

    {{-- ── TABLE CARD ──────────────────────────────────── --}}
    <div class="tbl-card afu">
        <div class="tbl-toolbar">
            <p style="font-size:.8rem;color:var(--gray);margin:0;flex:1;">
                Photos shown on the homepage carousel, in this order. Use the arrows to reorder.
            </p>

            <span class="tbl-count">{{ $this->photos->count() }} photos</span>

            <button wire:click="openCreate" class="btn-admin btn-admin-primary btn-admin-sm">
                + Add Photo
            </button>
        </div>

        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:50px;"></th>
                        <th>Label</th>
                        <th>Sub-label</th>
                        <th>Link</th>
                        <th style="width:90px;">Order</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->photos as $index => $photo)
                        <tr wire:key="photo-{{ $photo->id }}">
                            <td>
                                <img src="{{ Storage::disk('public')->url($photo->image_path) }}"
                                     style="width:42px;height:42px;border-radius:6px;object-fit:cover;flex-shrink:0;" />
                            </td>
                            <td>
                                <div class="td-name">{{ $photo->label }}</div>
                            </td>
                            <td>
                                <span style="font-size:.8rem;color:var(--gray);">{{ $photo->sub_label ?? '—' }}</span>
                            </td>
                            <td style="max-width:180px;">
                                <div style="font-size:.75rem;color:var(--gray);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $photo->link_url ?? '—' }}
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;gap:.25rem;">
                                    <button wire:click="moveUp({{ $photo->id }})"
                                            @if($index === 0) disabled @endif
                                            class="btn-icon" title="Move up" aria-label="Move {{ $photo->label }} up">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    </button>
                                    <button wire:click="moveDown({{ $photo->id }})"
                                            @if($index === $this->photos->count() - 1) disabled @endif
                                            class="btn-icon" title="Move down" aria-label="Move {{ $photo->label }} down">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="td-actions">
                                    <button wire:click="openEdit({{ $photo->id }})"
                                            class="btn-icon" title="Edit" aria-label="Edit {{ $photo->label }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $photo->id }})"
                                            wire:confirm="Delete '{{ $photo->label }}'? This cannot be undone."
                                            class="btn-icon btn-icon-danger" title="Delete" aria-label="Delete {{ $photo->label }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="6">No showcase photos yet. Add one to feature it on the homepage carousel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── MODAL ───────────────────────────────────────── --}}
    @if($showModal)
    <x-ui.modal model="showModal" :title="$isEditing ? 'Edit Photo' : 'Add New Photo'">
            <div class="modal-body">
                <form wire:submit="save" id="showcase-form">
                    <div class="form-group">
                        <label class="form-label">Label</label>
                        <input wire:model="label" type="text" placeholder="e.g. Alfonso Lista Attire Pair"
                               class="form-input {{ $errors->has('label') ? 'error' : '' }}" />
                        @error('label') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sub-label</label>
                        <input wire:model="sub_label" type="text" placeholder="e.g. Textiles"
                               class="form-input" />
                        @error('sub_label') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Link URL</label>
                        <input wire:model="link_url" type="text" placeholder="e.g. /attires?municipality=Alfonso+Lista"
                               class="form-input" />
                        @error('link_url') <p class="form-error">{{ $message }}</p> @enderror
                        <p style="font-size:.72rem;color:var(--gray-lt);margin-top:.25rem;">
                            Leave blank if the photo shouldn't link anywhere when clicked.
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ $isEditing ? 'Replace Photo' : 'Photo' }}</label>
                        @if($image && $image->isPreviewable())
                            <div style="position:relative;margin-bottom:.5rem;width:100px;">
                                <img src="{{ $image->temporaryUrl() }}" style="width:100px;height:100px;object-fit:cover;border-radius:.5rem;" />
                                <button type="button" wire:click="$set('image', null)"
                                        style="position:absolute;top:.25rem;right:.25rem;width:1.5rem;height:1.5rem;border-radius:50%;background:rgba(0,0,0,.65);color:#fff;border:none;cursor:pointer;font-size:.8rem;line-height:1;"
                                        title="Remove selected image" aria-label="Remove selected image">✕</button>
                            </div>
                        @elseif($image)
                            {{-- Selected but not previewable — no preview, validation error shows below. --}}
                        @elseif($isEditing && $existingImagePath)
                            <div style="margin-bottom:.5rem;">
                                <img src="{{ Storage::disk('public')->url($existingImagePath) }}" style="width:100px;height:100px;object-fit:cover;border-radius:.5rem;" />
                            </div>
                        @endif
                        <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;height:100px;border:2px dashed {{ $errors->has('image') ? 'var(--red)' : 'var(--tan)' }};border-radius:.5rem;cursor:pointer;background:var(--cream);transition:border-color 150ms;"
                               onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='{{ $errors->has('image') ? 'var(--red)' : 'var(--tan)' }}'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:var(--gray-lt);margin-bottom:.375rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span style="font-size:.78rem;color:var(--gray);">
                                @if($image)
                                    <span style="color:var(--gold);font-weight:600;">{{ $image->getClientOriginalName() }}</span>
                                @else
                                    Click to upload · JPG or PNG, max 10 MB
                                @endif
                            </span>
                            <input wire:model="image" type="file" accept="image/jpeg,image/png,image/jpg" style="display:none;" />
                        </label>
                        <div wire:loading wire:target="image" style="font-size:.75rem;color:var(--gray);margin-top:.25rem;">Uploading image…</div>
                        @error('image') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-foot">
                <button type="button" wire:click="$set('showModal', false)" class="btn-admin btn-admin-outline">
                    Cancel
                </button>
                <button type="submit" form="showcase-form"
                        class="btn-admin btn-admin-primary"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Add Photo' }}</span>
                    <span wire:loading>Saving…</span>
                </button>
            </div>
    </x-ui.modal>
    @endif

</div>
