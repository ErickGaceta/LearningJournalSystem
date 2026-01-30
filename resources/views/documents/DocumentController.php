<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'division_units' => 'nullable|string|max:255',
            'positions' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'hours' => 'required|integer|min:1',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:255',
            'registration_fee' => 'required|string|max:50',
            'travel_expenses' => 'required|string|max:50',
            'topics' => 'required|string',
            'insights' => 'required|string',
            'application' => 'required|string',
            'challenges' => 'required|string',
            'appreciation' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['employee_id'] = Auth::user()->employee_id;

        $document = Document::create($validated);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Learning journal submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        // Check if user owns this document
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        // Check if user owns this document
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        // Check if user owns this document
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'hours' => 'required|integer|min:1',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:255',
            'registration_fee' => 'required|string|max:50',
            'travel_expenses' => 'required|string|max:50',
            'topics' => 'required|string',
            'insights' => 'required|string',
            'application' => 'required|string',
            'challenges' => 'required|string',
            'appreciation' => 'required|string',
        ]);

        $document->update($validated);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * THIS IS THE FIXED DESTROY METHOD
     */
    public function destroy(Document $document)
    {
        // Check if user owns this document
        if ($document->user_id !== Auth::id()) {
            return redirect()->route('documents.index')
                ->with('error', 'Unauthorized: You cannot delete this document.');

        }

        try {
            // Store document title before deletion
            $documentTitle = $document->title;
            
            // Delete the document
            $document->delete();
            
            // Redirect to documents index with success message
           return redirect()->route('views.dashboard');
                ->with('success', "Document '{$documentTitle}' has been successfully deleted.");
                
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Document deletion failed', [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Redirect back with error message
            return redirect()->route('dashboard');
                ->with('error', 'Failed to delete document. Please try again.');
        }
    }

    /**
     * Show print preview for the document.
     */
    public function printPreview(Document $document)
    {
        // Check if user owns this document
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('documents.print-preview', compact('document'));
    }
}
