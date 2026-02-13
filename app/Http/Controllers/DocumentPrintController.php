<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Pdf\LearningJournalPDF;
use Illuminate\Support\Facades\Auth;

class DocumentPrintController extends Controller
{
    public function previewPdf(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $document->load(['module', 'user.divisionUnit', 'user.position']);

        $user     = $document->user;
        $module   = $document->module;
        $date     = now()->format('Y-m-d');
        $filename = ($module?->title ?? 'Learning Journal')
            . ' - ' . $date
            . ' - ' . $document->fullname
            . '.pdf';

        $html = view('pages.user.documents.print', compact('document', 'user', 'module', 'date'))->render();

        $pdf = new LearningJournalPDF(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,   // unicode
            'UTF-8',
            false
        );

        $pdf->SetCreator('DOST CAR Learning Journal System');
        $pdf->SetAuthor($document->fullname);
        $pdf->SetTitle(($module?->title ?? 'Learning Journal') . ' - ' . $date);
        $pdf->SetSubject('Learning Journal');
        $pdf->SetKeywords('DOST, CAR, Learning Journal, ' . ($module?->title ?? ''));

        // Set paths before enabling header/footer
        $pdf->setHeaderImagePath(public_path('documents/header.PNG'));
        $pdf->setFooterImagePath(public_path('documents/footer.PNG'));

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        // Tune top margin (30) to match your header image height in mm
        // Tune footer margin (25) to match your footer image height in mm
        $pdf->SetMargins(10, 30, 10);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(25);
        $pdf->SetAutoPageBreak(true, 30);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('dejavusans', '', 10);

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        $document->withoutEvents(function () use ($document) {
            $document->forceFill([
                'isPrinted' => 1,
                'printedAt' => now()->toDateString(),
            ])->save();
        });

        return response($pdf->Output($filename, 'I'))
            ->header('Content-Type', 'application/pdf');
    }
}
