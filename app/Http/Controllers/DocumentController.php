<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Document;
use App\Models\TrainingModule;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $year = now()->year;
        $showArchived = $request->boolean('archived');

        $documents = Document::with('module')
            ->where('user_id', $userId)
            ->where('isArchived', $showArchived)
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
            'year',
            'showArchived'
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

        $document->load(['module', 'user.divisionUnit', 'user.position']);

        return view('pages.user.documents.show', compact('document'));
    }

    public function restore(Document $document): RedirectResponse
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $document->update(['isArchived' => false]);

        return redirect()
            ->route('user.documents.index', ['archived' => true])
            ->with('success', 'Learning journal restored successfully.');
    }

    public function update(Request $request, Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'travel_expenses' => 'nullable|numeric|min:0',
            'topics'          => 'nullable|string',
            'insights'        => 'nullable|string',
            'application'     => 'nullable|string',
            'challenges'      => 'nullable|string',
            'appreciation'    => 'nullable|string',
        ]);

        $document->update($validated);

        return redirect()->route('user.documents.show', $document)
            ->with('success', 'Document updated successfully!');
    }

    public function archive(Document $document): RedirectResponse
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $document->update(['isArchived' => true]);

        return redirect()
            ->route('user.documents.index')
            ->with('success', 'Learning journal archived successfully.');
    }

    public function archiveIndex(): View
    {
        $documents = Document::where('user_id', Auth::id())
            ->where('isArchived', true)
            ->latest()
            ->get();

        return view('pages.user.documents.index', compact('documents'));
    }

    public function preview(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $document->load(['module', 'user.divisionUnit', 'user.position']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.user.documents.pdf', compact('document'));

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="learning-journal-' . $document->id . '.pdf"',
        ]);
    }
}
