<div>

    {{-- ── STAT CARDS ──────────────────────────────────── --}}
    <div class="stat-grid">
        @php
        $statIcons = [
            '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>',
            '<path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
            '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
        ];
        $statColors = ['var(--gold)', 'var(--green)', 'var(--orange)'];
        @endphp
        @foreach($this->stats as $i => $stat)
        <div class="stat-card afu" style="animation-delay:{{ $i * 60 }}ms;">
            <div class="stat-icon" style="color:{{ $statColors[$i] ?? 'var(--gold)' }};">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.375rem;height:1.375rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    {!! $statIcons[$i] ?? '' !!}
                </svg>
            </div>
            <div class="stat-num">{{ $stat['value'] }}</div>
            <div class="stat-label">{{ $stat['title'] }}</div>
        </div>
        @endforeach
        {{-- 4th stat: interactive guides --}}
        <div class="stat-card afu" style="animation-delay:180ms;">
            <div class="stat-icon" style="color:#8B5CF6;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.375rem;height:1.375rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
            <div class="stat-num">{{ $this->guidesCount }}</div>
            <div class="stat-label">Interactive Guides</div>
        </div>
    </div>

    {{-- ── CHARTS ──────────────────────────────────────── --}}
    <div class="dash-grid" style="margin-bottom:1.25rem;">
        <div class="dash-card afu" style="animation-delay:200ms;">
            <p class="dash-section-title">Dances by Category</p>
            <div class="bar-chart">
                @php $maxD = max(1, collect($this->danceByCategory)->max('value')); @endphp
                @foreach($this->danceByCategory as $row)
                    <div class="bar-row">
                        <span class="bar-label">{{ $row['label'] }}</span>
                        <span class="bar-track">
                            <span class="bar-fill bar-fill-gold" style="width:{{ round($row['value'] / $maxD * 100) }}%;"></span>
                        </span>
                        <span class="bar-value">{{ $row['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="dash-card afu" style="animation-delay:260ms;">
            <p class="dash-section-title">Attires by Municipality <span style="font-weight:400;color:var(--gray-lt);font-size:.72rem;">(top 6)</span></p>
            <div class="bar-chart">
                @php $maxM = max(1, collect($this->attireByMunicipality)->max('value') ?? 0); @endphp
                @forelse($this->attireByMunicipality as $row)
                    <div class="bar-row">
                        <span class="bar-label">{{ $row['label'] }}</span>
                        <span class="bar-track">
                            <span class="bar-fill bar-fill-green" style="width:{{ round($row['value'] / $maxM * 100) }}%;"></span>
                        </span>
                        <span class="bar-value">{{ $row['value'] }}</span>
                    </div>
                @empty
                    <p style="font-size:.85rem;color:var(--gray-lt);font-style:italic;">No attire data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── BOTTOM GRID ─────────────────────────────────── --}}
    <div class="dash-grid">

        {{-- Quick Actions --}}
        <div class="dash-card afu" style="animation-delay:240ms;">
            <p class="dash-section-title">Quick Actions</p>
            <div class="quick-links">
                <a href="{{ route('admin.dances') }}" class="quick-link" style="text-decoration:none;">
                    <span class="ql-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    <span>
                        <div class="ql-label">Add Dance</div>
                        <div class="ql-sub">New Dance entry</div>
                    </span>
                </a>
                <a href="{{ route('admin.attires') }}" class="quick-link" style="text-decoration:none;">
                    <span class="ql-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    <span>
                        <div class="ql-label">Add Attire</div>
                        <div class="ql-sub">New Attire entry</div>
                    </span>
                </a>
                <a href="{{ route('admin.dances') }}" class="quick-link" style="text-decoration:none;">
                    <span class="ql-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/>
                        </svg>
                    </span>
                    <span>
                        <div class="ql-label">Manage Dances</div>
                        <div class="ql-sub">{{ $this->stats[0]['value'] }} entries</div>
                    </span>
                </a>
                <a href="{{ route('admin.attires') }}" class="quick-link" style="text-decoration:none;">
                    <span class="ql-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4z"/>
                        </svg>
                    </span>
                    <span>
                        <div class="ql-label">Manage Attires</div>
                        <div class="ql-sub">{{ $this->stats[1]['value'] }} entries</div>
                    </span>
                </a>
            </div>
        </div>

        {{-- Recent Activity (Audit Log) --}}
        <div class="dash-card afu" style="animation-delay:300ms;">
            <p class="dash-section-title">Recent Activity</p>
            <div class="activity-list">
                @forelse($this->recentActivity as $log)
                    @php
                        $colors = ['create' => 'var(--green)', 'update' => 'var(--gold)', 'delete' => 'var(--red)'];
                        $icons  = ['create' => '＋', 'update' => '✎', 'delete' => '✕'];
                        $dot    = $colors[$log->action] ?? 'var(--gray-lt)';
                    @endphp
                    <div class="activity-item">
                        <div class="act-dot" style="background:{{ $dot }};"></div>
                        <div>
                            <div class="act-text">
                                <strong style="font-weight:600;color:{{ $dot }};">{{ ucfirst($log->action) }}</strong>
                                {{ ucfirst($log->resource_type) }}
                                "<em>{{ $log->resource_name }}</em>"
                            </div>
                            <div class="act-time">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p style="font-size:.875rem; color:var(--gray-lt); font-style:italic; padding:.5rem 0;">No activity yet. Start adding dances and attires.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
