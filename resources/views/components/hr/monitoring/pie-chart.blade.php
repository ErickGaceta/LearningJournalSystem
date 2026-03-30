@props(['chartValue', 'maxValue' => 100, 'strokeColor', 'textLabel'])

@php
$radius = 40;
$circumference = 2 * pi() * $radius;
$gaugeSpan = 240;
$visibleArc = $circumference * ($gaugeSpan / 360);

$value = min(max($chartValue, 0), $maxValue);
$percentage = $maxValue > 0 ? $value / $maxValue : 0;
$percentage = min($percentage, 1.0);
$strokeColor = $strokeColor ?? 'lime';
$displayPct = $value >= $maxValue ? 100 : round($percentage * 100);
$dashOffset = $value >= $maxValue ? 0.01 : $visibleArc * (1 - $percentage);


// Text wrapping
$words = explode(' ', $textLabel ?? '');
$lines = [];
$current = '';
$maxChars = 14;

foreach ($words as $word) {
$test = $current ? "$current $word" : $word;
if (strlen($test) > $maxChars && $current !== '') {
$lines[] = $current;
$current = $word;
} else {
$current = $test;
}
}
if ($current) $lines[] = $current;

$lineHeight = 16;
$totalHeight = count($lines) * $lineHeight;
$startY = 118 - ($totalHeight / 2);
@endphp

<svg width="150" height="150" viewBox="0 0 150 150">
    <!-- Background track -->
    <circle cx="75" cy="75" r="{{ $radius }}"
        fill="none" stroke="black" stroke-width="20" stroke-linecap="round"
        stroke-dasharray="{{ $visibleArc }} {{ $circumference }}"
        stroke-dashoffset="0"
        transform="rotate(150 75 75)" />

    <!-- Foreground value -->
    <circle cx="75" cy="75" r="{{ $radius }}"
        fill="none" stroke="{{ $strokeColor }}" stroke-width="12" stroke-linecap="round"
        stroke-dasharray="{{ $visibleArc }} {{ $circumference }}"
        stroke-dashoffset="{{ $dashOffset }}"
        transform="rotate(150 75 75)" />

    <!-- Percentage -->
    <text x="75" y="75"
        text-anchor="middle" dominant-baseline="middle"
        font-size="20" font-family="Arial Black, sans-serif"
        fill="{{ $strokeColor }}">
        {{ $chartValue === 0 && $maxValue <= 1 ? '—' : $displayPct . '%' }}
    </text>

    <!-- Wrapped label -->
    <text text-anchor="middle" font-size="11" font-family="Arial Black, sans-serif" fill="{{ $strokeColor }}">
        @foreach ($lines as $i => $line)
        <tspan x="75" y="{{ $startY + ($i * $lineHeight) }}">{{ $line }}</tspan>
        @endforeach
    </text>
</svg>