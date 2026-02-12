<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Document;
use App\Models\TrainingModule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    // ========== Dashboard ==========
    public function dashboard()
    {
        $user = Auth::user();
        $users = User::where('user_type', 'user')->get();

        $myAssignments = Assignment::where('user_id', $user->id)
            ->with('module')
            ->latest()
            ->paginate(10);

        $datestart = TrainingModule::get('datestart');
        $dateend = TrainingModule::get('dateend');
        $interval = $datestart->diff($dateend);

        $trainingModule = TrainingModule::all();

        $activeAssignments = Assignment::where('user_id', $user->id)
            ->whereHas('module', function ($query) {
                $query->where('dateend', '>=', now());
            })
            ->count();

        $completedAssignments = Assignment::where('user_id', $user->id)
            ->whereHas('module', function ($query) {
                $query->where('dateend', '<', now());
            })
            ->count();

        $userTrainings = Assignment::all();

        $myDocuments = Document::where('user_id', $user->id)->count();

        return view('pages.user.dashboard', compact(
            'userTrainings',
            'myAssignments',
            'activeAssignments',
            'completedAssignments',
            'myDocuments',
            'users',
            'interval',
            'trainingModule',
            'user'
        ));
    }

    // ========== Track Training ==========
    public function myTrainings()
    {
        $user = Auth::user();

        $trainings = Assignment::where('user_id', $user->id)
            ->with('module')
            ->latest()
            ->paginate(15);

        return view('pages.user.trainings.index', compact('trainings', 'user'));
    }

    public function showTraining(Assignment $assignment)
    {
        if ($assignment->user_id !== Auth::id()) {
            abort(403);
        }

        $assignment->load('module');

        return view('pages.user.trainings.show', compact('assignment'));
    }
}
