<div class="diamond-strip">
    @php $n = 80; $cols = ['#D4A574','#C85A17','#6B4423','#2D5016','#D4A574','#B8925D']; @endphp
    <svg viewBox="0 0 {{ $n * 14 }} 10" preserveAspectRatio="none">
        @for($i = 0; $i < $n; $i++)
            <polygon points="{{ $i*14+7 }},0 {{ $i*14+13 }},5 {{ $i*14+7 }},10 {{ $i*14+1 }},5" fill="{{ $cols[$i % count($cols)] }}" />
        @endfor
    </svg>
</div>
