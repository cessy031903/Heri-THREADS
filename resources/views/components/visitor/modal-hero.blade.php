{{-- Modal hero banner with optional zoomable background image --}}
@props([
    'imageUrl' => null,
    'imageAlt' => '',
    'gradientFrom' => '#2d5016',
    'gradientTo' => '#1a3a10',
    'patternId' => 'hero-pat',
])

<header {{ $attributes->merge(['class' => 'dmodal-hero']) }}>
    <div class="dmodal-hero-bg" style="background:linear-gradient(148deg,{{ $gradientFrom }} 0%,{{ $gradientTo }} 100%);">
        <svg style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none" viewBox="0 0 80 80" preserveAspectRatio="xMidYMid slice">
            <defs><pattern id="{{ $patternId }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><polygon points="10,1 19,10 10,19 1,10" fill="none" stroke="white" stroke-width="1.3" opacity="0.09"/><circle cx="10" cy="10" r="1.8" fill="white" opacity="0.063"/></pattern></defs>
            <rect width="80" height="80" fill="url(#{{ $patternId }})"/>
        </svg>
    </div>
    @if($imageUrl)
        <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" onerror="this.style.display='none'" class="dmodal-hero-img" />
    @endif
    <div class="dmodal-hero-shade"></div>

    @if($imageUrl || isset($actions))
        <div class="dmodal-hero-actions">
            @if($imageUrl)
                <x-visitor.image-zoom-btn />
            @endif
            {{ $actions ?? '' }}
        </div>
    @endif

    {{ $slot }}
</header>
