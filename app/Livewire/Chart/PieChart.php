<?php

namespace App\Livewire\Chart;

use Livewire\Component;
use Livewire\Attributes\Computed;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class PieChart extends Component
{
    public $datasets;

    #[Computed]
    public function chart()
    {
        return Chartjs::build()
            ->name("UserRegistrationsChart")
            ->livewire()
            ->model("datasets")
            ->type("pie");
    }

    public function render()
    {
        $this->getData();
        return view('livewire.chart.pie-chart');
    }

    public function getData()
    {
        $data = []; //get data from models
        $labels = []; // label datasets
        
        $this->datasets = [
            'datasets' => [
                [
                    "label" => "User Registrations",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $data
                ]
            ],
            'labels' => $labels
        ];
    }
}
