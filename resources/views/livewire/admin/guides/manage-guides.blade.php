<div>

    {{-- ── TABLE CARD ──────────────────────────────────── --}}
    <div class="tbl-card afu">
        <div class="tbl-toolbar">
            <span class="tbl-count">{{ $this->guides->count() }} guides</span>
            <span style="flex:1;"></span>
            <button wire:click="openCreate" class="btn-admin btn-admin-primary btn-admin-sm">
                + New Guide
            </button>
        </div>

        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:60px;"></th>
                        <th>Municipality</th>
                        <th>Title</th>
                        <th style="width:110px;">Hotspots</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->guides as $guide)
                        <tr wire:key="guide-{{ $guide->id }}">
                            <td>
                                @if($guide->image_path)
                                    <img src="{{ Storage::disk('public')->url($guide->image_path) }}"
                                         style="width:46px;height:34px;border-radius:6px;object-fit:cover;" />
                                @else
                                    <div class="swatch" style="width:46px;height:34px;border-radius:6px;background:linear-gradient(135deg,#2d5016,#1a3a10);"></div>
                                @endif
                            </td>
                            <td><div class="td-name">{{ $guide->municipality }}</div></td>
                            <td><span style="font-size:.85rem;color:var(--char);">{{ $guide->title }}</span></td>
                            <td>
                                <span class="badge-admin bf">{{ $guide->hotspots_count }} points</span>
                            </td>
                            <td>
                                <div class="td-actions">
                                    <button wire:click="openEdit({{ $guide->id }})"
                                            class="btn-icon" title="Edit" aria-label="Edit {{ $guide->municipality }} guide">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $guide->id }})"
                                            wire:confirm="Delete the '{{ $guide->municipality }}' guide? This cannot be undone."
                                            class="btn-icon btn-icon-danger" title="Delete" aria-label="Delete {{ $guide->municipality }} guide">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="5">No interactive guides yet. Create one to add hotspots.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── MODAL ───────────────────────────────────────── --}}
    @if($showModal)
    <x-ui.modal model="showModal" :lg="true" :title="$isEditing ? 'Edit Guide' : 'New Interactive Guide'">
            <div class="modal-body">
                <form wire:submit="save" id="guide-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Municipality *</label>
                            <select wire:model="municipality" class="form-input form-select {{ $errors->has('municipality') ? 'error' : '' }}">
                                <option value="">Select municipality</option>
                                @foreach($municipalities as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                            @error('municipality') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Title *</label>
                            <input wire:model="title" type="text" placeholder="e.g. Traditional Paired Dress — Interactive Guide"
                                   class="form-input {{ $errors->has('title') ? 'error' : '' }}" />
                            @error('title') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Instruction (optional)</label>
                        <input wire:model="instruction" type="text" placeholder="e.g. Hover or tap each number to explore"
                               class="form-input" />
                        @error('instruction') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ $isEditing && $existingImagePath ? 'Replace Image (optional)' : 'Background Image (optional)' }}</label>
                        @if($existingImagePath && ! $image)
                            <img src="{{ Storage::disk('public')->url($existingImagePath) }}"
                                 style="width:160px;height:90px;object-fit:cover;border-radius:.5rem;margin-bottom:.5rem;" />
                        @endif
                        <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;height:90px;border:2px dashed {{ $errors->has('image') ? 'var(--red)' : 'var(--tan)' }};border-radius:.5rem;cursor:pointer;background:var(--cream);">
                            <span style="font-size:.78rem;color:var(--gray);">
                                @if($image)
                                    <span style="color:var(--gold);font-weight:600;">{{ $image->getClientOriginalName() }}</span>
                                @else
                                    Click to upload · JPG or PNG, max 10 MB (falls back to a woven pattern)
                                @endif
                            </span>
                            <input wire:model="image" type="file" accept="image/jpeg,image/png,image/jpg" style="display:none;" />
                        </label>
                        @error('image') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- ── Hotspots editor ── --}}
                    @php
                        $previewUrl = null;
                        if ($image) {
                            try { $previewUrl = $image->temporaryUrl(); } catch (\Throwable $e) { $previewUrl = null; }
                        } elseif ($existingImagePath) {
                            $previewUrl = Storage::disk('public')->url($existingImagePath);
                        }
                    @endphp

                    <div class="form-group">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;">
                            <label class="form-label" style="margin:0;">Hotspots</label>
                            <button type="button" wire:click="addHotspot" class="btn-admin btn-admin-outline btn-admin-sm">
                                + Add manually
                            </button>
                        </div>

                        {{-- Visual point-and-click stage --}}
                        @if($previewUrl)
                            <p style="font-size:.72rem;color:var(--gray);margin-bottom:.6rem;">
                                <strong>Click</strong> on the image to drop a hotspot, then fill in its label below.
                                <strong>Drag</strong> a pin to reposition it.
                            </p>
                            <div class="hs-stage"
                                 x-data="{
                                    drag: null, last: null,
                                    pct(e) {
                                        const r = $refs.img.getBoundingClientRect();
                                        return {
                                            x: Math.min(100, Math.max(0, ((e.clientX - r.left) / r.width)  * 100)),
                                            y: Math.min(100, Math.max(0, ((e.clientY - r.top)  / r.height) * 100)),
                                        };
                                    },
                                    add(e) {
                                        if (e.target !== $refs.img) return;   /* ignore clicks on pins / after a drag */
                                        const p = this.pct(e);
                                        $wire.addHotspotAt(p.x.toFixed(1), p.y.toFixed(1));
                                    },
                                    start(i) { this.drag = i; this.last = null; },
                                    move(e) {
                                        if (this.drag === null) return;
                                        const p = this.pct(e);
                                        const pin = $refs['pin' + this.drag];
                                        if (pin) { pin.style.left = p.x + '%'; pin.style.top = p.y + '%'; }
                                        this.last = p;
                                    },
                                    end() {
                                        if (this.drag === null) return;
                                        const i = this.drag; this.drag = null;
                                        if (this.last) $wire.moveHotspot(i, this.last.x.toFixed(1), this.last.y.toFixed(1));
                                        this.last = null;
                                    }
                                 }"
                                 x-on:click="add($event)"
                                 x-on:mousemove.window="move($event)"
                                 x-on:mouseup.window="end()">
                                <img x-ref="img" class="hs-img" src="{{ $previewUrl }}" alt="Guide background" draggable="false" />
                                @foreach($hotspots as $i => $hotspot)
                                    <span class="hs-pin"
                                          x-ref="pin{{ $i }}"
                                          style="left:{{ $hotspot['pos_x'] }}%; top:{{ $hotspot['pos_y'] }}%;"
                                          x-on:mousedown.stop.prevent="start({{ $i }})"
                                          x-on:click.stop
                                          title="{{ $hotspot['label'] ?: 'Hotspot '.($i + 1) }}">{{ $i + 1 }}</span>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size:.72rem;color:var(--gray);margin-bottom:.75rem;">
                                Upload a background image above to place hotspots by clicking, or add them manually and set X/Y percentages.
                            </p>
                        @endif

                        {{-- Hotspot detail rows --}}
                        <div style="margin-top:.85rem;">
                        @forelse($hotspots as $i => $hotspot)
                            <div wire:key="hs-{{ $i }}" style="border:1px solid var(--tan);border-radius:.65rem;padding:.85rem;margin-bottom:.75rem;background:var(--cream);">
                                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.6rem;">
                                    <span class="badge-admin bf" style="flex-shrink:0;">{{ $i + 1 }}</span>
                                    <input wire:model="hotspots.{{ $i }}.label" type="text" placeholder="Label (e.g. Headdress)"
                                           class="form-input {{ $errors->has('hotspots.'.$i.'.label') ? 'error' : '' }}" style="flex:1;" />
                                    <button type="button" wire:click="removeHotspot({{ $i }})"
                                            class="btn-admin btn-admin-danger btn-admin-sm" style="flex-shrink:0;">Remove</button>
                                </div>
                                @error('hotspots.'.$i.'.label') <p class="form-error" style="margin-top:-.35rem;margin-bottom:.5rem;">{{ $message }}</p> @enderror

                                <div class="form-row" style="margin-bottom:.6rem;">
                                    <div class="form-group" style="margin:0;">
                                        <label class="form-label">X % <span style="font-weight:400;color:var(--gray-lt);">(click image)</span></label>
                                        <input wire:model="hotspots.{{ $i }}.pos_x" type="number" min="0" max="100" step="0.1" class="form-input" />
                                        @error('hotspots.'.$i.'.pos_x') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="form-group" style="margin:0;">
                                        <label class="form-label">Y % <span style="font-weight:400;color:var(--gray-lt);">(click image)</span></label>
                                        <input wire:model="hotspots.{{ $i }}.pos_y" type="number" min="0" max="100" step="0.1" class="form-input" />
                                        @error('hotspots.'.$i.'.pos_y') <p class="form-error">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="form-group" style="margin-bottom:.6rem;">
                                    <label class="form-label">Description</label>
                                    <textarea wire:model="hotspots.{{ $i }}.description" class="form-input"
                                              placeholder="What this part of the attire is…" style="min-height:60px;"></textarea>
                                </div>

                                <div class="form-group" style="margin:0;">
                                    <label class="form-label">Link to Attire (optional)</label>
                                    <select wire:model="hotspots.{{ $i }}.attire_id" class="form-input form-select">
                                        <option value="">— None —</option>
                                        @foreach($this->attireOptions as $opt)
                                            <option value="{{ $opt->id }}">{{ $opt->name_general }} ({{ ucfirst($opt->gender) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @empty
                            <p style="font-size:.8rem;color:var(--gray);font-style:italic;">No hotspots yet. Click the image (or “Add manually”) to mark a point.</p>
                        @endforelse
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-foot">
                <button type="button" wire:click="$set('showModal', false)" class="btn-admin btn-admin-outline">Cancel</button>
                <button type="submit" form="guide-form" class="btn-admin btn-admin-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Create Guide' }}</span>
                    <span wire:loading>Saving…</span>
                </button>
            </div>
    </x-ui.modal>
    @endif

</div>
