<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Training;         
use App\Models\TrainingEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CertificateController extends Controller
{
    
    public function preview(Request $request, int $trainingId, int $employeeId)
    {
        $data = $this->resolveData($trainingId, $employeeId);

        return view('hr.monitoring.certificate', $data);
    }

   
    public function download(Request $request, int $trainingId, int $employeeId)
    {
        $data = $this->resolveData($trainingId, $employeeId);

        $pdf = app('dompdf.wrapper')
            ->loadView('hr.monitoring.certificate', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Certificate_'
            . str($data['employeeName'])->slug('_') . '_'
            . str($data['trainingTitle'])->slug('_')
            . '.pdf';

        return $pdf->download($filename);
    }

  
    private function resolveData(int $trainingId, int $employeeId): array
    {
        
        $training = Training::with('employees')->findOrFail($trainingId);
        $employee = $training->employees()->findOrFail($employeeId);

        return [
           
            'employeeName'     => $employee->full_name
                                    ?? trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')),
            'employeePosition' => $employee->position  ?? $employee->job_title ?? null,
            'employeeDivision' => $employee->division  ?? $employee->department ?? null,

           
            'trainingTitle'    => $training->title,
            'venue'            => $training->venue,
            'conductor'        => $training->conductor ?? $training->trainer ?? null,
            'dateFrom'         => optional($training->date_from)->format('F j, Y'),
            'dateTo'           => optional($training->date_to)->format('F j, Y'),
            'hours'            => $training->hours   ?? null,
            'units'            => $training->units   ?? null,

            
            'trainingId'       => $trainingId,
            'employeeId'       => $employeeId,
        ];
    }
}
