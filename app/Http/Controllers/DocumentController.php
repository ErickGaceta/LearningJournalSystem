<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Document;
use App\Models\TrainingModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('topics', 'like', "%{$search}%");
            });
        }

        $userAssignments = Assignment::with('module')
            ->where('user_id', Auth::id())
            ->get();

        $documents = $query->latest()->paginate(15)->withQueryString();
        $documentCount = Document::where('user_id', Auth::id())->count();
        $totalHours = Assignment::where('user_id', Auth::id())
            ->with('module')
            ->get()
            ->sum(fn($assignment) => $assignment->module->hours);

        $year = now()->year;

        $totalYearlyDocument = Document::where('user_id', Auth::id())
            ->whereYear('created_at', $year)
            ->count();

        return view('pages.user.documents.index', compact('userAssignments', 'documents', 'documentCount', 'totalHours', 'totalYearlyDocument', 'year'));
    }

    public function create()
    {
        return view('pages.user.documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:255',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'hours' => 'required|numeric|min:0',
            'topics' => 'nullable|string',
            'registration_fee' => 'nullable|numeric|min:0',
            'travel_expenses' => 'nullable|numeric|min:0',
            'insights' => 'nullable|string',
            'application' => 'nullable|string',
            'challenges' => 'nullable|string',
            'appreciation' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        Document::create($validated);

        return redirect()->route('user.documents.index')
            ->with('success', 'Learning journal submitted successfully!');
    }

    public function show(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.user.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.user.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:255',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'hours' => 'required|numeric|min:0',
            'topics' => 'nullable|string',
            'registration_fee' => 'nullable|numeric|min:0',
            'travel_expenses' => 'nullable|numeric|min:0',
            'insights' => 'nullable|string',
            'application' => 'nullable|string',
            'challenges' => 'nullable|string',
            'appreciation' => 'nullable|string',
        ]);

        $document->update($validated);

        return redirect()->route('user.documents.index')
            ->with('success', 'Document updated successfully!');
    }

    public function destroy(Document $document)
    {
        try {
            if ($document->user_id !== Auth::id()) {
                abort(403);
            }

            $documentTitle = $document->title;
            $document->delete();

            return redirect()
                ->route('user.documents.index')
                ->with('success', "Document '{$documentTitle}' has been successfully deleted.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'An error occurred while deleting the document. Please try again.');
        }
    }
}
