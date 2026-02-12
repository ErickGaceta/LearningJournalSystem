<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Elibyy\TCPDF\TCPDF as BaseTCPDF;
use Illuminate\Support\Facades\Auth;

class DocumentPrintController extends Controller
{
    public function previewPdf(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $document->load(['module', 'user.divisionUnit', 'user.position']);

        $user   = $document->user;
        $module = $document->module;
        $date   = now()->format('Y-m-d');
        $filename = ($module?->title ?? 'Learning Journal') . ' - ' . $date . ' - ' . $document->fullname . '.pdf';

        $html = view('pages.user.documents.print', compact('document','user','module','date'))->render();

        $config = config('tcpdf');

        // create new PDF document
        $pdf = new BaseTCPDF(
            $config['page_orientation'],
            $config['page_units'],
            $config['page_format'],
            $config['unicode'],
            $config['encoding'],
            false
        );

        // ---------------------------------------------------------
        // Set document information
        $pdf->SetCreator('DOST CAR Learning Journal System');
        $pdf->SetAuthor($document->fullname);
        $pdf->SetTitle(($module?->title ?? 'Learning Journal') . ' - ' . $date);
        $pdf->SetSubject('Learning Journal');
        $pdf->SetKeywords('DOST, CAR, Learning Journal, ' . ($module?->title ?? ''));

        // ---------------------------------------------------------
        // Set default header data using images
        $headerFile = __DIR__ . '../../../public/documents/header.PNG';;
        $footerFile = __DIR__ . '../../../public/documents/footer.PNG';;

        $pdf->SetHeaderData(
            $headerFile, // logo
            210,         // logo width in mm (fits page width)
            '',          // title (empty, we only want image)
            '',          // string under header
            [0,0,0],     // text color
            [0,0,0]      // line color
        );

        $pdf->setFooterData(
            $footerFile,
            210,
            '',
            '',
            [0,0,0], [0,0,0]);

        // ---------------------------------------------------------
        // Set fonts for header and footer
        $pdf->setHeaderFont([$config['font_directory'] ?: 'dejavusans', '', 10]);
        $pdf->setFooterFont([$config['font_directory'] ?: 'dejavusans', '', 10]);

        // ---------------------------------------------------------
        // Set margins
        $pdf->SetMargins($config['margin_left'], 55, $config['margin_right']); // top margin allows header
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(35);
        $pdf->SetAutoPageBreak(true, 40);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('dejavusans', '', 10);

        // ---------------------------------------------------------
        // Add a page and write HTML
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        // Mark as printed
        $document->update([
            'isPrinted' => 1,
            'printedAt' => now(),
        ]);

        return response($pdf->Output($filename, 'I'))
            ->header('Content-Type', 'application/pdf');
    }
}
