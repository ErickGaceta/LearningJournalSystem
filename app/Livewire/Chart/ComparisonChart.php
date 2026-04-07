<?php

namespace App\Livewire\Chart;

use Livewire\Component;

class ComparisonChart extends Component
{
    public float  $chartValue1  = 0;
    public float  $chartValue2  = 0;
    public float  $chartValue3  = 0;
    public string $strokeColor1 = 'orange';
    public string $strokeColor2 = 'green';
    public string $strokeColor3 = 'violet';
    public string $label1       = '';
    public string $label2       = '';
    public string $label3       = '';
    public string $textLabel    = '';
    public array  $chartConfig  = [];

    public function mount(
        float  $chartValue1  = 0,
        float  $chartValue2  = 0,
        float  $chartValue3  = 0,
        string $strokeColor1 = 'orange',
        string $strokeColor2 = 'green',
        string $strokeColor3 = 'violet',
        string $label1       = '',
        string $label2       = '',
        string $label3       = '',
        string $textLabel    = ''
    ): void {
        $this->chartValue1  = $chartValue1;
        $this->chartValue2  = $chartValue2;
        $this->chartValue3  = $chartValue3;
        $this->strokeColor1 = $strokeColor1;
        $this->strokeColor2 = $strokeColor2;
        $this->strokeColor3 = $strokeColor3;
        $this->label1       = $label1;
        $this->label2       = $label2;
        $this->label3       = $label3;
        $this->textLabel    = $textLabel;
        $this->computeConfig();
    }

    public function computeConfig(): void
    {
        $total = $this->chartValue1 + $this->chartValue2 + $this->chartValue3;

        // If all zero, show equal empty slices so the chart still renders
        $data = $total > 0
            ? [$this->chartValue1, $this->chartValue2, $this->chartValue3]
            : [1, 1, 1];

        $this->chartConfig = [
            'data'     => $data,
            'isEmpty'  => $total === 0.0,
            'colors'   => [$this->strokeColor1, $this->strokeColor2, $this->strokeColor3],
            'labels'   => [
                $this->label1 . ': ' . (int) $this->chartValue1,
                $this->label2 . ': ' . (int) $this->chartValue2,
                $this->label3 . ': ' . (int) $this->chartValue3,
            ],
            'textLabel' => $this->textLabel,
        ];
    }

    public function render()
    {
        return view('livewire.chart.comparison-chart');
    }
}