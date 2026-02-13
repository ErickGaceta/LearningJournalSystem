<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Position;
use App\Models\DivisionUnit;
use App\Models\TrainingModule;
use App\Models\Document;
use App\Models\Assignment;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ========== Dashboard ==========
    public function dashboard()
    {
        $now = Carbon::now();

        return view('pages.admin.dashboard', [
            'totalModules' => TrainingModule::count(),
            'activeModules' => TrainingModule::where('dateend', '>=', $now)->count(),
            'completedModules' => TrainingModule::where('dateend', '<', $now)->count(),
            'documents' => Document::where('user_id', Auth::id())->count(),
            'activeAssignments' => Assignment::whereHas('module', fn($q) =>
                $q->where('datestart', '<=', $now)
                  ->where('dateend', '>=', $now)
            )->count(),
        ]);
    }

    // ========== User Management ==========
    public function usersIndex()
    {
        $users = User::whereIn('user_type', ['user', 'hr'])
            ->with(['position', 'divisionUnit'])
            ->latest()
            ->paginate(20);

        return view('pages.admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('pages.admin.users.create', [
            'positions' => Position::orderBy('positions')->get(),
            'divisions' => DivisionUnit::orderBy('division_units')->get(),
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Male,Female'],
            'id_positions' => ['required', 'exists:positions,id'],
            'id_division_units' => ['required', 'exists:division_units,id'],
            'employee_type' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'is_active' => ['nullable', 'boolean'],
            'user_type' => ['required', 'in:hr,user'],
        ]);

        $generatedPassword = Str::password(12); // More secure, mixed case + symbols

        $validated['password'] = $generatedPassword;
        $validated['is_active'] = $request->boolean('is_active');

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User created! Temporary password: {$generatedPassword}");
    }

    public function showUser(User $user)
    {
        return view('pages.admin.users.show', [
            'user' => $user,
            'positions' => Position::orderBy('positions')->get(),
            'divisions' => DivisionUnit::orderBy('division_units')->get(),
        ]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'max:255', 'unique:users,employee_id,' . $user->id],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Male,Female'],
            'id_positions' => ['required', 'exists:positions,id'],
            'id_division_units' => ['required', 'exists:division_units,id'],
            'employee_type' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_active' => ['nullable', 'boolean'],
            'user_type' => ['required', 'in:hr,user'],
        ]);

        // Remove password if empty (let 'hashed' cast handle hashing if provided)
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        // Optional: Prevent deleting yourself or check for related data
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    // ========== Position Management ==========
    public function positionsIndex()
    {
        $positions = Position::withCount('users')
            ->orderBy('positions')
            ->paginate(15);

        return view('pages.admin.positions.index', compact('positions'));
    }

    public function createPosition()
    {
        return view('pages.admin.positions.create');
    }

    public function storePosition(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'positions' => ['required', 'string', 'max:255', 'unique:positions'],
        ]);

        Position::create($validated);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function showPosition(Position $position)
    {
        $position->loadCount('users');

        return view('pages.admin.positions.show', compact('position'));
    }

    public function editPosition(Position $position)
    {
        return view('pages.admin.positions.edit', compact('position'));
    }

    public function updatePosition(Request $request, Position $position): RedirectResponse
    {
        $validated = $request->validate([
            'positions' => ['required', 'string', 'max:255', 'unique:positions,positions,' . $position->id],
        ]);

        $position->update($validated);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position updated successfully.');
    }

    public function destroyPosition(Position $position): RedirectResponse
    {
        // Optional: Prevent deletion if users are assigned
        if ($position->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete position with assigned users.']);
        }

        $position->delete();

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position deleted successfully.');
    }

    // ========== Division Management ==========
    public function divisionsIndex()
    {
        $divisions = DivisionUnit::withCount('users')
            ->orderBy('division_units')
            ->paginate(15);

        return view('pages.admin.divisions.index', compact('divisions'));
    }

    public function createDivision()
    {
        return view('pages.admin.divisions.create');
    }

    public function storeDivision(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'division_units' => ['required', 'string', 'max:255', 'unique:division_units'],
        ]);

        DivisionUnit::create($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully.');
    }

    public function showDivision(DivisionUnit $division)
    {
        $division->loadCount('users');

        return view('pages.admin.divisions.show', compact('division'));
    }

    public function editDivision(DivisionUnit $division)
    {
        return view('pages.admin.divisions.edit', compact('division'));
    }

    public function updateDivision(Request $request, DivisionUnit $division): RedirectResponse
    {
        $validated = $request->validate([
            'division_units' => ['required', 'string', 'max:255', 'unique:division_units,division_units,' . $division->id],
        ]);

        $division->update($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully.');
    }

    public function destroyDivision(DivisionUnit $division): RedirectResponse
    {
        // Optional: Prevent deletion if users are assigned
        if ($division->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete division with assigned users.']);
        }

        $division->delete();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully.');
    }
}
