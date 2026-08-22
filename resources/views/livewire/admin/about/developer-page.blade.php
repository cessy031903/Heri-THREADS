<div>

    <div class="tbl-card afu" style="padding:1.75rem;">
        <h2 style="font-family:var(--font-display);font-size:1.1rem;margin:0 0 1rem;">Developer</h2>

        <p style="font-size:.78rem;color:var(--gray-lt);margin:0 0 1.25rem;font-style:italic;">
            Placeholder details below — replace with real name, photo, and contact info before launch.
        </p>

        <div style="display:flex;flex-direction:column;gap:1rem;">
            @foreach($developers as $dev)
                <div style="display:flex;gap:1rem;align-items:center;padding:1rem;border:1px solid var(--tan);border-radius:.625rem;">
                    <div style="width:64px;height:64px;border-radius:50%;background:var(--tan);flex-shrink:0;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                        @if($dev['photo'])
                            <img src="{{ $dev['photo'] }}" alt="{{ $dev['name'] }}" style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            <span style="font-size:1.25rem;font-weight:700;color:var(--gold);">
                                {{ strtoupper(mb_substr($dev['name'], 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:.95rem;font-weight:700;color:var(--char);margin:0;">{{ $dev['name'] }}</p>
                        <p style="font-size:.78rem;color:var(--gold);font-weight:600;margin:.125rem 0 .5rem;">{{ $dev['role'] }}</p>
                        <p style="font-size:.8rem;color:var(--gray);margin:0;">{{ $dev['email'] }} · {{ $dev['contact'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
