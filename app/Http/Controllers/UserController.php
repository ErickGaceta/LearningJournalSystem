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
use Illuminate\Http\RedirectResponse;
use App\Models\Signature;
use Illuminate\Support\Facades\Storage;
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

    public function uploadSignature(Request $request): RedirectResponse
    {
        $request->validate([
            'signature_base64' => ['required', 'string'],
        ]);

        $user = Auth::user();

        // Delete old file
        $existing = Signature::where('employee_id', $user->id)->first();
        if ($existing && file_exists(public_path($existing->signature_path))) {
            unlink(public_path($existing->signature_path));
        }

        $base64   = $request->input('signature_base64');
        $data     = base64_decode(explode(',', $base64)[1]);
        $folder   = public_path('signatures/' . $user->id);
        $filename = 'signatures/' . $user->id . '/' . str($user->first_name)->slug() . '-signature.png';

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        file_put_contents(public_path($filename), $data);

        Signature::updateOrCreate(
            ['employee_id' => $user->id],
            ['signature_path' => $filename]
        );

        return back()->with('success', 'Signature uploaded successfully!');
    }

    // ========== Track Training ==========
    public function myTrainings()
    {
        $user = Auth::user();

        $trainings = Assignment::where('user_id', $user->id)
            ->with(['module.documents' => fn($q) => $q->where('user_id', $user->id)->where('isArchived', 0)])
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

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }

        User::find($user->id)->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'password' => $request->filled('password') ? Hash::make($validated['password']) : $user->password,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function markAllNotificationsRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}
