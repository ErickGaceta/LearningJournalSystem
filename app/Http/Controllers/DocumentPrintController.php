<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\WordXmlTemplateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DocumentPrintController extends Controller
{
    protected WordXmlTemplateService $wordService;

    public function __construct(WordXmlTemplateService $wordService)
    {
        $this->wordService = $wordService;
    }

    /**
     * Export a document to Word (.docx) using XML template replacement.
     */
    public function exportWord(Document $document)
    {
        try {
            // Security: only owner can export
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

    /**
     * Show print preview in browser (PDF or HTML view)
     */
    public function preview(Document $document)
    {
        // Security: only owner can preview
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('documents.print-preview', compact('document'));
    }
}
