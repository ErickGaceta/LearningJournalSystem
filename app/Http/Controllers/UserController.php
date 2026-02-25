<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Assignment;
use App\Models\Document;
use App\Models\TrainingModule;
use App\Models\Position;
use App\Models\DivisionUnit;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    // ========== Dashboard ==========
    public function dashboard()
    {
        $user = Auth::user();

        $assignmentCounts = Assignment::where('user_id', $user->id)
            ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN EXISTS (
                SELECT 1 FROM training_module
                WHERE training_module.id = assignments.module_id
                AND training_module.dateend >= NOW()
            ) THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN EXISTS (
                SELECT 1 FROM training_module
                WHERE training_module.id = assignments.module_id
                AND training_module.dateend < NOW()
            ) THEN 1 ELSE 0 END) as completed
        ")
            ->first();

        $activeAssignments    = $assignmentCounts->active ?? 0;
        $completedAssignments = $assignmentCounts->completed ?? 0;

        $myDocuments = Document::where('user_id', $user->id)->count();

        $userTrainings = Assignment::where('user_id', $user->id);

        $trainings = Assignment::where('user_id', $user->id)
            ->with('module:id,title,datestart,dateend')
            ->latest()
            ->paginate(15);

        return view('pages.user.dashboard', compact(
            'activeAssignments',
            'completedAssignments',
            'myDocuments',
            'user',
            'trainings',
            'userTrainings'
        ));
    }

    // ========== Track Training ==========
    public function myTrainings()
    {
        $user = Auth::user();

        $trainings = Assignment::where('user_id', $user->id)
            ->with(['module.documents' => fn($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->get();

        return view('pages.user.trainings.index', compact('trainings', 'user'));
    }

    // ========== Profile Management ==========
    public function editProfile()
    {
        $user = Auth::user();
        $positions = Position::orderBy('positions')->get();
        $divisions = DivisionUnit::orderBy('division_units')->get();

        return view('pages.user.profile.edit', compact('user', 'positions', 'divisions'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Male,Female'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Check current password if trying to change password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }

        $user->first_name = $validated['first_name'];
        $user->middle_name = $validated['middle_name'];
        $user->last_name = $validated['last_name'];
        $user->gender = $validated['gender'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
