<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\TrainingModule;
use App\Models\User;
use App\Models\Document;
use Elibyy\TCPDF\TCPDF;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function index()
    {
        $user   = User::find(Auth::id());
        $module = TrainingModule::first();
        $assignments = Assignment::where('module_id', $module->id)
            ->where('user_id', $user->id)
            ->get();
        $documents = Document::where('user_id', Auth::id())->get();

        $documentName = $module->title;
        $employeeName = trim($user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name);
        $date = now()->format('Y-m-d');
        $filename = $documentName . ' - ' . $date . ' ' . $employeeName . '.pdf';

        $data = [
            'title' => $documentName . ' ' . $date,
            'employeeName' => $employeeName,
            'moduleTitle' => $documentName,
            'assignments' => $assignments,
            'trainingModule' => $module,
            'documents' => $documents,
        ];

        $html = view('pages.user.documents.print', $data)->render();

        // Extend TCPDF to override header/footer
        $pdf = new class(config('tcpdf')) extends TCPDF {
            public function Header() {
                $this->Image(public_path('documents/header.PNG'), 0, 0, 210);
            }

            public function Footer() {
                $footerHeight = 30;
                $this->SetY(-$footerHeight);
                $this->Image(public_path('documents/footer.PNG'), 0, $this->GetY(), 210);
            }
        };

        $pdf->SetTitle($data['title']);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        return response($pdf->Output($filename, 'I'))
            ->header('Content-Type', 'application/pdf');
    }
}
