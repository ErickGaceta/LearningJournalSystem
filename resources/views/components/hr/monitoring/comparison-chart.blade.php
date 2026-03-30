@props([
'chartValue1',
'chartValue2',
'chartValue3' => null,
'strokeColor1' => 'lime',
'strokeColor2' => 'cyan',
'strokeColor3' => 'violet',
'label1' => 'Value 1',
'label2' => 'Value 2',
'label3' => 'Value 3',
'textLabel'
])

@php
$gaugeSpan = 240;
$hasThird = !is_null($chartValue3);
$max = $hasThird
? max($chartValue1, $chartValue2, $chartValue3, 1)
: max($chartValue1, $chartValue2, 1);

// Radii
$r1 = 50;
$r2 = 45;
$r3 = 40;

// Background centered to cover all active rings
$rBg = $hasThird ? ($r1 + $r3) / 2 : ($r1 + $r2) / 2;
$circBg = 2 * pi() * $rBg;
$arcBg = $circBg * ($gaugeSpan / 360);

// Ring 1
$circ1 = 2 * pi() * $r1;
$arc1 = $circ1 * ($gaugeSpan / 360);
$pct1 = min($chartValue1 / $max, 1.0);
$dash1 = $chartValue1 === 0 ? '0.01 ' . $circ1 : $arc1 . ' ' . $circ1;
$offset1 = $chartValue1 === 0 ? 0 : ($chartValue1 >= $max ? 0.01 : $arc1 * (1 - $pct1));

// Ring 2
$circ2 = 2 * pi() * $r2;
$arc2 = $circ2 * ($gaugeSpan / 360);
$pct2 = min($chartValue2 / $max, 1.0);
$dash2 = $chartValue2 === 0 ? '0.01 ' . $circ2 : $arc2 . ' ' . $circ2;
$offset2 = $chartValue2 === 0 ? 0 : ($chartValue2 >= $max ? 0.01 : $arc2 * (1 - $pct2));

// Ring 3 (optional)
if ($hasThird) {
$circ3 = 2 * pi() * $r3;
$arc3 = $circ3 * ($gaugeSpan / 360);
$pct3 = min($chartValue3 / $max, 1.0);
$dash3 = $chartValue3 === 0 ? '0.01 ' . $circ3 : $arc3 . ' ' . $circ3;
$offset3 = $chartValue3 === 0 ? 0 : ($chartValue3 >= $max ? 0.01 : $arc3 * (1 - $pct3));
}

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
$startY = 135 - (count($lines) * $lineHeight / 2);
@endphp

<svg width="160" height="160" viewBox="0 0 160 160" x-data="{
    hovered: null,
    labels: {
        1: '{{ $label1 }}: {{ $chartValue1 }}',
        2: '{{ $label2 }}: {{ $chartValue2 }}',
        @if($hasThird) 3: '{{ $label3 }}: {{ $chartValue3 }}', @endif
    }
}">

    <!-- Background band covering all rings -->
    <circle cx="80" cy="80" r="{{ $rBg }}"
        fill="none" stroke="#222"
        stroke-width="{{ $hasThird ? 20 : 14 }}"
        stroke-linecap="round"
        stroke-dasharray="{{ $arcBg }} {{ $circBg }}"
        stroke-dashoffset="0"
        transform="rotate(150 80 80)" />

    <!-- Ring 1 -->
    <circle cx="80" cy="80" r="{{ $r1 }}"
        fill="none" stroke="{{ $strokeColor1 }}"
        stroke-linecap="round"
        stroke-dasharray="{{ $dash1 }}"
        stroke-dashoffset="{{ $offset1 }}"
        transform="rotate(150 80 80)"
        style="cursor: pointer; transition: stroke-width 0.2s ease, opacity 0.2s ease;"
        :style="hovered === null
            ? 'stroke-width: 5; opacity: 1'
            : (hovered === 1 ? 'stroke-width: 6; opacity: 1' : 'stroke-width: 3; opacity: 0.3')"
        x-on:mouseenter="hovered = 1"
        x-on:mouseleave="hovered = null" />

    <!-- Ring 2 -->
    <circle cx="80" cy="80" r="{{ $r2 }}"
        fill="none" stroke="{{ $strokeColor2 }}"
        stroke-linecap="round"
        stroke-dasharray="{{ $dash2 }}"
        stroke-dashoffset="{{ $offset2 }}"
        transform="rotate(150 80 80)"
        style="cursor: pointer; transition: stroke-width 0.2s ease, opacity 0.2s ease;"
        :style="hovered === null
            ? 'stroke-width: 5; opacity: 1'
            : (hovered === 2 ? 'stroke-width: 6; opacity: 1' : 'stroke-width: 3; opacity: 0.3')"
        x-on:mouseenter="hovered = 2"
        x-on:mouseleave="hovered = null" />

    @if ($hasThird)
    <!-- Ring 3 -->
    <circle cx="80" cy="80" r="{{ $r3 }}"
        fill="none" stroke="{{ $strokeColor3 }}"
        stroke-linecap="round"
        stroke-dasharray="{{ $dash3 }}"
        stroke-dashoffset="{{ $offset3 }}"
        transform="rotate(150 80 80)"
        style="cursor: pointer; transition: stroke-width 0.2s ease, opacity 0.2s ease;"
        :style="hovered === null
            ? 'stroke-width: 5; opacity: 1'
            : (hovered === 3 ? 'stroke-width: 6; opacity: 1' : 'stroke-width: 3; opacity: 0.3')"
        x-on:mouseenter="hovered = 3"
        x-on:mouseleave="hovered = null" />
    @endif

    <!-- Center label -->
    <foreignObject x="30" y="57" width="100" height="46">
        <div xmlns="http://www.w3.org/1999/xhtml"
            class="w-full h-full flex items-center justify-center gap-1 text-center pointer-events-none">
            <span class="text-xs font-bold inline-block transition-all duration-200"
                :class="hovered === null ? 'opacity-100 scale-100' : (hovered === 1 ? 'opacity-100 scale-125' : 'opacity-30 scale-90')"
                style="color: {{ $strokeColor1 }}">{{ $chartValue1 }}</span>
            <span class="text-xs inline-block transition-all duration-200"
                :class="hovered === null ? 'opacity-40 scale-100' : 'opacity-10 scale-90'"
                style="color: white">/</span>
            <span class="text-xs font-bold inline-block transition-all duration-200"
                :class="hovered === null ? 'opacity-100 scale-100' : (hovered === 2 ? 'opacity-100 scale-125' : 'opacity-30 scale-90')"
                style="color: {{ $strokeColor2 }}">{{ $chartValue2 }}</span>
            @if ($hasThird)
            <span class="text-xs inline-block transition-all duration-200"
                :class="hovered === null ? 'opacity-40 scale-100' : 'opacity-10 scale-90'"
                style="color: white">/</span>
            <span class="text-xs font-bold inline-block transition-all duration-200"
                :class="hovered === null ? 'opacity-100 scale-100' : (hovered === 3 ? 'opacity-100 scale-125' : 'opacity-30 scale-90')"
                style="color: {{ $strokeColor3 }}">{{ $chartValue3 }}</span>
            @endif
        </div>
    </foreignObject>

    <!-- Wrapped label -->
    <text text-anchor="middle" font-size="10" font-family="Arial Black, sans-serif" fill="white">
        @foreach ($lines as $i => $line)
        <tspan x="80" y="{{ $startY + ($i * $lineHeight) }}">{{ $line }}</tspan>
        @endforeach
    </text>

    <g x-show="hovered !== null" x-cloak>
        <rect
            x="20" y="4" width="120" height="22" rx="4"
            fill="#1a1a1a" stroke="#444" stroke-width="1" opacity="0.95" />
        <text
            x="80" y="18"
            text-anchor="middle"
            dominant-baseline="middle"
            font-size="10"
            font-family="Arial, sans-serif"
            fill="white"
            x-text="hovered ? labels[hovered] : ''" />
    </g>

</svg>