<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentPrintController extends Controller
{
    public function preview(Document $document)
    {
        return Pdf::loadView(
            'documents.print',
            compact('document')
        )->stream();
    }
}
