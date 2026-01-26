<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:500',
            'title' => 'required|string|max:500',
            'hours' => 'required|integer|min:1',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'registration_fee' => 'required|string|max:100',
            'travel_expenses' => 'required|string|max:100',
            'topics' => 'required|string',
            'insights' => 'required|string',
            'application' => 'required|string',
            'challenges' => 'required|string',
            'appreciation' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();

        $document = Document::create($validated);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document created successfully!');
    }

    public function show(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'fullname' => 'required|string|max:500',
            'title' => 'required|string|max:500',
            'hours' => 'required|integer|min:1',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'registration_fee' => 'required|string|max:100',
            'travel_expenses' => 'required|string|max:100',
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

    public function destroy(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully!');
    }
}