<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::latest()->paginate(10);
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
            'title' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'hours' => 'required|numeric|min:0',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:255',
            'registration_fee' => 'required|numeric|min:0',
            'travel_expenses' => 'required|numeric|min:0',
            'topics' => 'required|string',
            'insights' => 'required|string',
            'application' => 'required|string',
            'challenges' => 'required|string',
            'appreciation' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();

        Document::create($validated);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'hours' => 'required|numeric|min:0',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:255',
            'registration_fee' => 'required|numeric|min:0',
            'travel_expenses' => 'required|numeric|min:0',
            'topics' => 'required|string',
            'insights' => 'required|string',
            'application' => 'required|string',
            'challenges' => 'required|string',
            'appreciation' => 'required|string',
        ]);

        $document->update($validated);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        try {
            // Optional: Check if user has permission to delete
            // Uncomment if you have authorization policies
            // $this->authorize('delete', $document);

            $documentTitle = $document->title;
            $document->delete();

            return redirect()
                ->route('documents.index')
                ->with('success', "Document '{$documentTitle}' has been successfully deleted.");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while deleting the document. Please try again.');
        }
    }

    /**
     * Show print preview for the specified document.
     */
    public function printPreview(Document $document)
    {
        return view('documents.print-preview', compact('document'));
    }
}
