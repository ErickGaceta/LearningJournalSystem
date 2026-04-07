<?php

namespace App\Livewire\Chart;

use Livewire\Component;

class GaugeChart extends Component
{
    public float  $chartValue  = 0;
    public float  $maxValue    = 100;
    public string $strokeColor = 'lime';
    public string $textLabel   = '';
    public array  $chartConfig = [];

    public function mount(
        float  $chartValue  = 0,
        float  $maxValue    = 100,
        string $strokeColor = 'lime',
        string $textLabel   = ''
    ): void {
        $this->chartValue  = $chartValue;
        $this->maxValue    = $maxValue;
        $this->strokeColor = $strokeColor;
        $this->textLabel   = $textLabel;
        $this->computeConfig();
    }

    public function computeConfig(): void
    {
        $value      = min(max($this->chartValue, 0), $this->maxValue);
        $percentage = $this->maxValue > 0 ? $value / $this->maxValue : 0;
        $percentage = min($percentage, 1.0);

        $gaugeSpan    = 240;                        // visible arc in degrees
        $valueArc     = $percentage * $gaugeSpan;
        $remainingArc = (1 - $percentage) * $gaugeSpan;
        $gapArc       = 360 - $gaugeSpan;           // 120° transparent bottom gap

        $displayPct = $value >= $this->maxValue
            ? 100
            : (int) round($percentage * 100);

        $centerText = ($this->chartValue === 0.0 && $this->maxValue <= 1)
            ? '—'
            : $displayPct . '%';

        $this->chartConfig = [
            'data'        => [$valueArc, $remainingArc, $gapArc],
            'strokeColor' => $this->strokeColor,
            'centerText'  => $centerText,
            'textLabel'   => $this->textLabel,
        ];
    }

    public function updatedChartValue(): void { $this->computeConfig(); }
    public function updatedMaxValue(): void   { $this->computeConfig(); }

    public function render()
    {
        return view('livewire.chart.gauge-chart');
    }
}