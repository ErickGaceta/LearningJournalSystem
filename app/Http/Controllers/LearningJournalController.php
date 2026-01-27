<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningJournal;

class LearningJournalController extends Controller
{
    /**
     * Display the learning journal form.
     */
    public function create()
    {
        return view('learning-journal.create');
    }

    /**
     * Store a newly created learning journal in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'hours' => 'required|numeric|min:0',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'reg_fee' => 'required|numeric|min:0',
            'travel_expenses' => 'required|numeric|min:0',
            'learning' => 'required|string',
            'gained' => 'required|string',
            'apply' => 'required|string',
            'challenge' => 'required|string',
            'appreciate' => 'required|string',
        ]);

        // Add the authenticated user's ID
        $validated['user_id'] = auth()->id();

        // Create the learning journal entry
        $journal = LearningJournal::create($validated);

        // Redirect with success message
        return redirect()->route('learning-journal.index')
            ->with('success', 'Learning journal submitted successfully!');
    }

    /**
     * Display a listing of learning journals.
     */
    public function index()
    {
        $journals = LearningJournal::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('learning-journal.index', compact('journals'));
    }

    /**
     * Display the specified learning journal.
     */
    public function show(LearningJournal $learningJournal)
    {
        // Ensure user can only view their own journals
        if ($learningJournal->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('learning-journal.show', compact('learningJournal'));
    }
}