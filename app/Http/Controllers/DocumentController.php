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
        $userId = Auth::id();
        $year = now()->year;

        // Base document query with eager loading
        $documents = Document::with('modules')
            ->where('user_id', $userId)
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('venue', 'like', "%{$search}%")
                        ->orWhere('topics', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Total counts and hours, done efficiently
        $documentCount = $documents->total();

        $totalHours = Assignment::where('user_id', $userId)
            ->join('training_module', 'assignments.module_id', '=', 'training_module.id')
            ->sum('training_module.hours');

        $totalYearlyDocument = Document::where('user_id', $userId)
            ->whereYear('created_at', $year)
            ->count();

        return view('pages.user.documents.index', compact(
            'documents',
            'documentCount',
            'totalHours',
            'totalYearlyDocument',
            'year'
        ));
    }

    public function create(Assignment $assignment)
    {
        if ($assignment->user_id !== Auth::id()) {
            abort(403);
        }

        $assignment->load('module');

        return view('pages.user.documents.create', compact('assignment'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'travel_expenses' => 'nullable|numeric|min:0',
            'topics'          => 'required|string',
            'insights'        => 'required|string',
            'application'     => 'required|string',
            'challenges'      => 'required|string',
            'appreciation'    => 'required|string',
        ]);

        $validated['user_id']   = Auth::id();
        $validated['assignment_id'] = $assignment->id;
        $validated['module_id'] = $assignment->module_id;

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
            'module_id' => 'required|exists:training_module,id',
            'travel_expenses' => 'nullable|numeric|min:0',
            'topics' => 'nullable|string',
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
