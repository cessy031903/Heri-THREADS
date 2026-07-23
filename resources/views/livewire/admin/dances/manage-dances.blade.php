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
                       placeholder="Search dances…" />
            </div>

            <div class="tbl-filter">
                <select wire:model.live="categoryFilter">
                    <option value="">Municipality Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <span class="tbl-count" wire:loading.class="tbl-count-loading">
                {{ $this->dances->total() }} records
            </span>

            <button wire:click="openCreate" class="btn-admin btn-admin-primary btn-admin-sm">
                + Add Dance
            </button>
        </div>

        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:50px;"></th>
                        <th>
                            <button wire:click="sort('name')" class="sort-btn">
                                Native Dance
                                <span class="sort-ico">{{ $sortBy === 'name' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </button>
                        </th>
                        <th>Description</th>
                        <th>Video</th>
                        <th>
                            <button wire:click="sort('created_at')" class="sort-btn">
                                Added
                                <span class="sort-ico">{{ $sortBy === 'created_at' ? ($sortDir === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </button>
                        </th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                </thead>
                <tbody wire:loading.delay.class="tbl-count-loading" wire:target="search,categoryFilter,sort">
                    @forelse($this->dances as $dance)
                        <tr wire:key="dance-{{ $dance->id }}">
                            <td>
                                @php
                                    $pals=[['#7B3A10','#D4A574'],['#5C1F1F','#C85A17'],['#1A3A10','#4A8A2C'],['#3A2A10','#B8925D'],['#1A2A4A','#4A7AB5'],['#4A1A2A','#C86090'],['#2A3A10','#8AB54A'],['#3A1A10','#C89060']];
                                    $p=$pals[abs((int) $dance->id)%count($pals)];
                                @endphp
                                @if($dance->image_path)
                                    <img src="{{ Storage::disk('public')->url($dance->image_path) }}"
                                         style="width:42px;height:42px;border-radius:6px;object-fit:cover;flex-shrink:0;"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='block';" />
                                    <div class="swatch" style="display:none;width:42px;height:42px;background:linear-gradient(135deg,{{$p[0]}},{{$p[1]}});"></div>
                                @else
                                    <div class="swatch" style="width:42px;height:42px;background:linear-gradient(135deg,{{$p[0]}},{{$p[1]}});"></div>
                                @endif
                            </td>
                            <td>
                                <div class="td-name">{{ $dance->name }}</div>
                                <div class="td-sub">Added {{ $dance->created_at->format('M d, Y') }}</div>
                            </td>
                            <td style="max-width:280px;">
                                <div style="font-size:.8125rem;color:var(--gray);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $dance->description }}
                                </div>
                            </td>
                            <td>
                                <span style="font-size:.75rem;color:{{ $dance->video_url ? 'var(--green)' : 'var(--gray-lt)' }};">
                                    {{ $dance->video_url ? '✓ Set' : '— None' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size:.75rem;color:var(--gray-lt);">{{ $dance->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <div class="td-actions">
                                    <button wire:click="openEdit({{ $dance->id }})"
                                            class="btn-icon" title="Edit" aria-label="Edit {{ $dance->name }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $dance->id }})"
                                            wire:confirm="Delete '{{ $dance->name }}'? This cannot be undone."
                                            class="btn-icon btn-icon-danger" title="Delete" aria-label="Delete {{ $dance->name }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="6">No dances found{{ $search || $categoryFilter ? ' matching your filters' : '' }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($this->dances->hasPages())
            <div style="padding:.875rem 1.25rem; border-top:1px solid var(--tan); display:flex; align-items:center; justify-content:space-between;">
                <span style="font-size:.78rem; color:var(--gray);">
                    {{ $this->dances->firstItem() }}–{{ $this->dances->lastItem() }} of {{ $this->dances->total() }}
                </span>
                {{ $this->dances->links() }}
            </div>
        @endif
    </div>

    {{-- ── MODAL ───────────────────────────────────────── --}}
    @if($showModal)
    <x-ui.modal model="showModal" :title="$isEditing ? 'Edit Dance' : 'Add New Dance'">
            <div class="modal-body">
                <form wire:submit="save" id="dance-form">
                    <div class="form-group">
                        <label class="form-label">Native Dance *</label>
                        <input wire:model="name" type="text" placeholder="e.g. Pagaddut"
                               class="form-input {{ $errors->has('name') ? 'error' : '' }}" />
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select wire:model="category" class="form-input form-select">
                            <option value="">Select a category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea wire:model="description" class="form-input {{ $errors->has('description') ? 'error' : '' }}"
                                  placeholder="Describe the dance, its cultural significance…"></textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Region <span style="font-weight:400;color:var(--gray-lt);">(optional)</span></label>
                            <input wire:model="region" type="text" placeholder="e.g. Cordillera Administrative Region"
                                   class="form-input" />
                            @error('region') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Origin <span style="font-weight:400;color:var(--gray-lt);">(optional)</span></label>
                            <input wire:model="origin" type="text" placeholder="e.g. Ifugao Province"
                                   class="form-input" />
                            @error('origin') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cultural Meaning <span style="font-weight:400;color:var(--gray-lt);">(optional)</span></label>
                        <textarea wire:model="cultural_meaning" class="form-input"
                                  placeholder="What this dance represents within the community…" style="min-height:80px;"></textarea>
                        @error('cultural_meaning') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Historical Background <span style="font-weight:400;color:var(--gray-lt);">(optional)</span></label>
                        <textarea wire:model="historical_background" class="form-input"
                                  placeholder="Origins, traditions, and how it has been passed down…" style="min-height:80px;"></textarea>
                        @error('historical_background') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">YouTube URL <span style="font-weight:400;color:var(--gray-lt);">(optional)</span></label>
                        <input wire:model.live.debounce.500ms="video_url" type="url" placeholder="https://www.youtube.com/watch?v=…"
                               class="form-input {{ $errors->has('video_url') ? 'error' : '' }}" />
                        @error('video_url') <p class="form-error">{{ $message }}</p> @enderror
                        @if($this->videoUrlEmbed)
                            <div class="vid-wrap" style="margin-top:.5rem;aspect-ratio:16/9;">
                                <iframe src="{{ $this->videoUrlEmbed }}" title="YouTube preview" loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture"
                                        allowfullscreen style="width:100%;height:100%;border:0;border-radius:.5rem;"></iframe>
                            </div>
                        @elseif($video_url)
                            <p style="font-size:.72rem;color:var(--gray-lt);margin-top:.375rem;">Paste a valid YouTube link to see a preview.</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Upload Video <span style="font-weight:400;color:var(--gray-lt);">(optional — MP4/MOV/WebM, max 50 MB)</span></label>
                        @if($video && $video->isPreviewable())
                            <div style="position:relative;margin-bottom:.5rem;">
                                <video src="{{ $video->temporaryUrl() }}" controls preload="metadata"
                                       style="width:100%;max-height:180px;border-radius:.5rem;background:#000;"></video>
                                <button type="button" wire:click="$set('video', null)"
                                        style="position:absolute;top:.375rem;right:.375rem;width:1.75rem;height:1.75rem;border-radius:50%;background:rgba(0,0,0,.65);color:#fff;border:none;cursor:pointer;font-size:.9rem;line-height:1;"
                                        title="Remove selected video" aria-label="Remove selected video">✕</button>
                            </div>
                        @elseif($video)
                            {{-- Selected but not previewable (e.g. rejected file type) — no preview, validation error shows below. --}}
                        @elseif($isEditing && $existingVideoPath)
                            <div style="margin-bottom:.5rem;">
                                <video src="{{ Storage::disk('public')->url($existingVideoPath) }}" controls preload="metadata"
                                       style="width:100%;max-height:180px;border-radius:.5rem;background:#000;"></video>
                            </div>
                        @endif
                        <label style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;height:100px;border:2px dashed {{ $errors->has('video') ? 'var(--red)' : 'var(--tan)' }};border-radius:.5rem;cursor:pointer;background:var(--cream);transition:border-color 150ms;"
                               onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='{{ $errors->has('video') ? 'var(--red)' : 'var(--tan)' }}'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:var(--gray-lt);margin-bottom:.375rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span style="font-size:.78rem;color:var(--gray);">
                                @if($video)
                                    <span style="color:var(--gold);font-weight:600;">{{ $video->getClientOriginalName() }}</span>
                                @else
                                    Click to upload · MP4, MOV, or WebM, max 50 MB
                                @endif
                            </span>
                            <input wire:model="video" type="file" accept="video/mp4,video/quicktime,video/webm" style="display:none;" />
                        </label>
                        <div wire:loading wire:target="video" style="font-size:.75rem;color:var(--gray);margin-top:.25rem;">Uploading video…</div>
                        @error('video') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                </form>
            </div>
            <div class="modal-foot">
                <button type="button" wire:click="$set('showModal', false)" class="btn-admin btn-admin-outline">
                    Cancel
                </button>
                <button type="submit" form="dance-form"
                        class="btn-admin btn-admin-primary"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $isEditing ? 'Save Changes' : 'Add Dance' }}</span>
                    <span wire:loading>Saving…</span>
                </button>
            </div>
    </x-ui.modal>
    @endif

</div>
