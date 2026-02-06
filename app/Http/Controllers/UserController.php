<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    // ========== Dashboard ==========
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get all user trainings/documents for the dashboard
        $userTrainings = Document::where('user_id', $user->id)
            ->latest()
            ->get();
        
        $myAssignments = Assignment::where('user_id', $user->id)
            ->with('module')
            ->latest()
            ->paginate(10);

        $activeAssignments = Assignment::where('user_id', $user->id)
            ->whereHas('module', function($query) {
                $query->where('dateend', '>=', now());
            })
            ->count();

        $completedAssignments = Assignment::where('user_id', $user->id)
            ->whereHas('module', function($query) {
                $query->where('dateend', '<', now());
            })
            ->count();

        $myDocuments = Document::where('user_id', $user->id)->count();

        return view('pages.user.dashboard', compact(
            'userTrainings',
            'myAssignments',
            'activeAssignments',
            'completedAssignments',
            'myDocuments'
        ));
    }

    // ========== Track Training ==========
    public function myTrainings()
    {
        $user = Auth::user();
       
        // Get all user trainings for the trainings page
        $userTrainings = Document::where('user_id', $user->id)
            ->latest()
            ->get();
        
        $trainings = Assignment::where('user_id', $user->id)
            ->with('module')
            ->latest()
            ->paginate(15);

        return view('pages.user.trainings.index', compact('trainings', 'userTrainings'));
    }

    public function showTraining(Assignment $assignment)
    {
        // Ensure user can only view their own assignments
        if ($assignment->user_id !== Auth::id()) {
            abort(403);
        }

        $assignment->load('module');

        return view('pages.user.trainings.show', compact('assignment'));
    }
    
    // ========== Assigned Trainings Page ==========
    public function assignedTrainings()
    {
        $user = Auth::user();
        
        // Get all user trainings/documents
        $userTrainings = Document::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('pages.user.assigned-trainings', compact('userTrainings'));
    }
}