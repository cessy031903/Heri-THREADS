<div>

    {{-- ── TABLE CARD ──────────────────────────────────── --}}
    <div class="tbl-card afu">
        <div class="tbl-toolbar">
            <div class="tbl-search">
                <span class="tbl-search-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                </span>
                <input wire:model.live.debounce.300ms="search"
                       type="search"
                       placeholder="Search attires…" />
            </div>

            <div class="tbl-filter">
                <select wire:model.live="filterMunicipality">
                    <option value="">All Municipalities</option>
                    @foreach($municipalities as $m)
                        <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="tbl-filter">
                <select wire:model.live="filterGender">
                    <option value="">All Genders</option>
                    @foreach($genders as $g)
                        <option value="{{ $g['id'] }}">{{ $g['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <span class="tbl-count" wire:loading.class="tbl-count-loading">
                {{ $this->attires->total() }} records
            </span>

            <button wire:click="openCreate" class="btn-admin btn-admin-primary btn-admin-sm">
                + Add Attire
            </button>
        </div>

        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:50px;"></th>
                        <th>
                            <button wire:click="sort('name_general')" class="sort-btn">
                                General Name
                                <span class="sort-ico">{{ $sortBy === 'name_general' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </button>
                        </th>
                        <th>Dialect Name</th>
                        <th>
                            <button wire:click="sort('municipality')" class="sort-btn">
                                Municipality
                                <span class="sort-ico">{{ $sortBy === 'municipality' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </button>
                        </th>
                        <th>
                            <button wire:click="sort('gender')" class="sort-btn">
                                Gender
                                <span class="sort-ico">{{ $sortBy === 'gender' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </button>
                        </th>
                        <th>Source</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody wire:loading.delay.class="tbl-count-loading" wire:target="search,filterMunicipality,filterGender,sort">
                    @forelse($this->attires as $attire)
                        <tr wire:key="attire-{{ $attire->id }}">
                            <td>
                                @php
                                    $pals=[['#7B3A10','#D4A574'],['#5C1F1F','#C85A17'],['#1A3A10','#4A8A2C'],['#3A2A10','#B8925D'],['#1A2A4A','#4A7AB5'],['#4A1A2A','#C86090'],['#2A3A10','#8AB54A'],['#3A1A10','#C89060']];
                                    $p=$pals[((($attire->id + 10)%count($pals))+count($pals))%count($pals)];
                                @endphp
                                @if($attire->image_path)
                                    <img src="{{ Storage::disk('public')->url($attire->image_path) }}"
                                         style="width:42px;height:42px;border-radius:6px;object-fit:cover;flex-shrink:0;"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='block';" />
                                    <div class="swatch" style="display:none;width:42px;height:42px;background:linear-gradient(135deg,{{$p[0]}},{{$p[1]}});"></div>
                                @else
                                    <div class="swatch" style="width:42px;height:42px;background:linear-gradient(135deg,{{$p[0]}},{{$p[1]}});"></div>
                                @endif
                            </td>
                            <td>
                                <div class="td-name">{{ $attire->name_general }}</div>
                                <div class="td-sub">{{ $attire->municipality }}</div>
                            </td>
                            <td>
                                <em style="font-size:.84375rem;color:var(--gold);">{{ $attire->name_dialect }}</em>
                            </td>
                            <td>
                                <span style="font-size:.8rem;font-weight:600;color:var(--char);">{{ $attire->municipality }}</span>
                            </td>
                            <td>
                                <span class="badge-admin {{ $attire->gender === 'women' ? 'bf' : 'bm' }}">
                                    {{ ucfirst($attire->gender) }}
                                </span>
                            </td>
                            <td style="max-width:160px;">
                                <div style="font-size:.75rem;color:var(--gray);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $attire->source_info ?? '—' }}
                                </div>
                            </td>
                            <td>
                                <div class="td-actions">
                                    <button wire:click="openEdit({{ $attire->id }})"
                                            class="btn-icon" title="Edit" aria-label="Edit {{ $attire->name_general }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $attire->id }})"
                                            wire:confirm="Delete '{{ $attire->name_general }}'? This cannot be undone."
                                            class="btn-icon btn-icon-danger" title="Delete" aria-label="Delete {{ $attire->name_general }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="7">No attires found{{ $search || $filterMunicipality || $filterGender ? ' matching your filters' : '' }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($this->attires->hasPages())
            <div style="padding:.875rem 1.25rem; border-top:1px solid var(--tan); display:flex; align-items:center; justify-content:space-between;">
                <span style="font-size:.78rem; color:var(--gray);">
                    {{ $this->attires->firstItem() }}–{{ $this->attires->lastItem() }} of {{ $this->attires->total() }}
                </span>
                {{ $this->attires->links() }}
            </div>
        @endif
    </div>

    {{-- ── MODAL ───────────────────────────────────────── --}}
    @if($showModal)
    <x-ui.modal model="showModal" :lg="true" :title="$isEditing ? 'Edit Attire' : 'Add New Attire'">
            <div class="modal-body">
                <form wire:submit="save" id="attire-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">General Name *</label>
                            <input wire:model="name_general" type="text" placeholder="e.g. Wraparound Skirt"
                                   class="form-input {{ $errors->has('name_general') ? 'error' : '' }}" />
                            @error('name_general') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Dialect Name *</label>
                            <input wire:model="name_dialect" type="text" placeholder="e.g. Tapis"
                                   class="form-input {{ $errors->has('name_dialect') ? 'error' : '' }}" />
                            @error('name_dialect') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Municipality</label>
                            <select wire:model="municipality" class="form-input form-select">
                                <option value="">Select municipality</option>
                                @foreach($municipalities as $m)
                                    <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                                @endforeach
                            </select>
                            @error('municipality') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gender</label>
                            <select wire:model="gender" class="form-input form-select">
                                <option value="">Select gender</option>
                                @foreach($genders as $g)
                                    <option value="{{ $g['id'] }}">{{ $g['name'] }}</option>
                                @endforeach
                            </select>
                            @error('gender') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea wire:model="description" class="form-input {{ $errors->has('description') ? 'error' : '' }}"
                                  placeholder="Describe the attire, materials, cultural significance…"></textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Fabric / Material <span style="font-weight:400;color:var(--gray-lt);">(optional)</span></label>
                        <input wire:model="material" type="text" placeholder="e.g. Handwoven cotton, ikat-dyed threads"
                               class="form-input" />
                        @error('material') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cultural Significance <span style="font-weight:400;color:var(--gray-lt);">(optional)</span></label>
                        <textarea wire:model="cultural_significance" class="form-input"
                                  placeholder="The meaning, rituals, or status this attire conveys…" style="min-height:80px;"></textarea>
                        @error('cultural_significance') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Source / Reference</label>
                        <input wire:model="source_info" type="text" placeholder="e.g. National Museum of the Philippines"
                               class="form-input" />
                        @error('source_info') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            {{ $isEditing ? 'Replace Image (optional)' : 'Attire Image *' }}
                        </label>
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
                <button type="submit" form="attire-form"
                        class="btn-admin btn-admin-primary"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Add Attire' }}</span>
                    <span wire:loading>Saving…</span>
                </button>
            </div>
    </x-ui.modal>
    @endif

</div>
