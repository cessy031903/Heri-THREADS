@props(['pad' => true])

<div {{ $attributes->merge(['class' => 'ui-card' . ($pad ? ' ui-card-pad' : '')]) }}>
    {{ $slot }}
</div>
