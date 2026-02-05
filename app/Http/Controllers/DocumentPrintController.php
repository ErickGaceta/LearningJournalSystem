<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\WordXmlTemplateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DocumentPrintController extends Controller
{
    protected WordXmlTemplateService $wordService;

    public function __construct(WordXmlTemplateService $wordService)
    {
        $this->wordService = $wordService;
    }

    public function exportWord(Document $document)
    {
        try {
            if ($document->user_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }

            $document->update([
                'isPrinted' => 1,
                'printedAt' => now(),
            ]);

            $filePath = $this->wordService->generate($document);

            return response()->download(
                $filePath,
                basename($filePath),
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
            )->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Word Export Error: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());

            $errorMsg = 'Could not generate Word document. ';
            if (strpos($e->getMessage(), 'not a valid ZIP') !== false) {
                $errorMsg .= 'The template file may be corrupted.';
            } else {
                $errorMsg .= 'Error: ' . $e->getMessage();
            }

            return back()->with('error', $errorMsg);
        }
    }

    public function preview(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('pages.user.documents.print-preview', compact('document'));
    }
}