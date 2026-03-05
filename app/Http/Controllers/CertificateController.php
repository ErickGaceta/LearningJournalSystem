<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Training;         // adjust to your actual model
use App\Models\TrainingEmployee; // pivot / attendance model if separate
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CertificateController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    //  GET  /hr/monitoring/certificates/{training}/{employee}
    //  Preview the certificate inside the modal iframe
    // ──────────────────────────────────────────────────────────────
    public function preview(Request $request, int $trainingId, int $employeeId)
    {
        $data = $this->resolveData($trainingId, $employeeId);

        return view('hr.monitoring.certificate', $data);
    }

    // ──────────────────────────────────────────────────────────────
    //  GET  /hr/monitoring/certificates/{training}/{employee}/download
    //  Stream a PDF download of the certificate
    // ──────────────────────────────────────────────────────────────
    public function download(Request $request, int $trainingId, int $employeeId)
    {
        $data = $this->resolveData($trainingId, $employeeId);

        // Use Laravel's built-in Dompdf integration (barryvdh/laravel-dompdf)
        // or swap for Snappy / mPDF if that's what your project uses.
        $pdf = app('dompdf.wrapper')
            ->loadView('hr.monitoring.certificate', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Certificate_'
            . str($data['employeeName'])->slug('_') . '_'
            . str($data['trainingTitle'])->slug('_')
            . '.pdf';

        return $pdf->download($filename);
    }

    // ──────────────────────────────────────────────────────────────
    //  Shared data builder
    // ──────────────────────────────────────────────────────────────
    private function resolveData(int $trainingId, int $employeeId): array
    {
        // ── Option A: single Training model that has an `employees` relation ──
        $training = Training::with('employees')->findOrFail($trainingId);
        $employee = $training->employees()->findOrFail($employeeId);

        // ── If you have a pivot / separate attendance model, replace above with:
        //    $attendance = TrainingEmployee::with(['training','employee'])
        //                    ->where('training_id', $trainingId)
        //                    ->where('employee_id', $employeeId)
        //                    ->firstOrFail();
        //    $training = $attendance->training;
        //    $employee = $attendance->employee;

        return [
            // Employee
            'employeeName'     => $employee->full_name
                                    ?? trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')),
            'employeePosition' => $employee->position  ?? $employee->job_title ?? null,
            'employeeDivision' => $employee->division  ?? $employee->department ?? null,

            // Training
            'trainingTitle'    => $training->title,
            'venue'            => $training->venue,
            'conductor'        => $training->conductor ?? $training->trainer ?? null,
            'dateFrom'         => optional($training->date_from)->format('F j, Y'),
            'dateTo'           => optional($training->date_to)->format('F j, Y'),
            'hours'            => $training->hours   ?? null,
            'units'            => $training->units   ?? null,

            // IDs (passed through for back-links / actions)
            'trainingId'       => $trainingId,
            'employeeId'       => $employeeId,
        ];
    }
}
