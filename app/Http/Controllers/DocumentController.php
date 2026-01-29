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
    public function index(Request $request)
    {
        $query = Document::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('topics', 'like', "%{$search}%");
            });
        }

        // IMPORTANT: Use paginate() not get()
        $documents = $query->paginate(15)->withQueryString(); // Maintains search parameters

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
            'venue' => 'required|string|max:255',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'hours' => 'required|numeric|min:0',
            'topics' => 'nullable|string',
            // Add other fields as needed
        ]);

        $validated['user_id'] = Auth::id();

        Document::create($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Document created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        // Ensure user can only view their own documents
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        // Ensure user can only edit their own documents
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        // Ensure user can only update their own documents
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'hours' => 'required|numeric|min:0',
            'topics' => 'nullable|string',
            // Add other fields as needed
        ]);

        $document->update($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        // Ensure user can only delete their own documents
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }
}