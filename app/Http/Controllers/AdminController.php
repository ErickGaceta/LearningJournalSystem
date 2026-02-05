<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Position;
use App\Models\DivisionUnit;
use App\Models\TrainingModule;
use App\Models\Document;

class AdminController extends Controller
{
    // ========== Dashboard ==========
    public function dashboard()
    {
        $totalModules = TrainingModule::count();
        $activeModules = TrainingModule::where('dateend', '>=', now())->count();
        $completedModules = TrainingModule::where('dateend', '<', now())->count();
        $documents = Document::where('user_id', Auth::id())->count();

        return view('pages.admin.dashboard', compact(
            'totalModules',
            'activeModules',
            'completedModules',
            'documents'
        ));
    }

    // ========== User Management ==========
    public function usersIndex()
    {
        return view('pages.admin.users.index');
    }

    public function createUser()
    {
        $positions = Position::all();
        $divisions = DivisionUnit::all();

        return view('pages.admin.users.create', compact('positions', 'divisions'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'id_positions' => 'required|exists:positions,id',
            'id_division_units' => 'required|exists:division_units,id',
            'employee_type' => 'required|string|max:255',
            'roles' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
            'user_type' => 'required|in:hr,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', ucfirst($validated['user_type']) . ' user created successfully.');
    }

    public function editUser(User $user)
    {
        $positions = Position::all();
        $divisions = DivisionUnit::all();

        return view('pages.admin.users.edit', compact('user', 'positions', 'divisions'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255|unique:users,employee_id,' . $user->id,
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'id_positions' => 'required|exists:positions,id',
            'id_division_units' => 'required|exists:division_units,id',
            'employee_type' => 'required|string|max:255',
            'roles' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
            'user_type' => 'required|in:hr,user',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    // ========== Position Management ==========
    public function positionsIndex()
    {
        $positions = Position::withCount('users')->latest()->paginate(15);

        return view('pages.admin.positions.index', compact('positions'));
    }

    public function createPosition()
    {
        return view('pages.admin.positions.create');
    }

    public function storePosition(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position_name' => 'required|string|max:255|unique:positions',
        ]);

        Position::create($validated);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function editPosition(Position $position)
    {
        return view('pages.admin.positions.edit', compact('position'));
    }

    public function updatePosition(Request $request, Position $position): RedirectResponse
    {
        $validated = $request->validate([
            'position_name' => 'required|string|max:255|unique:positions,position_name,' . $position->id,
        ]);

        $position->update($validated);

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position updated successfully.');
    }

    public function destroyPosition(Position $position): RedirectResponse
    {
        $position->delete();

        return redirect()->route('admin.positions.index')
            ->with('success', 'Position deleted successfully.');
    }

    // ========== Division Management ==========
    public function divisionsIndex()
    {
        $divisions = DivisionUnit::withCount('users')->latest()->paginate(15);

        return view('pages.admin.divisions.index', compact('divisions'));
    }

    public function createDivision()
    {
        return view('pages.admin.divisions.create');
    }

    public function storeDivision(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'division_name' => 'required|string|max:255|unique:division_units',
        ]);

        DivisionUnit::create($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully.');
    }

    public function editDivision(DivisionUnit $division)
    {
        return view('pages.admin.divisions.edit', compact('division'));
    }

    public function updateDivision(Request $request, DivisionUnit $division): RedirectResponse
    {
        $validated = $request->validate([
            'division_name' => 'required|string|max:255|unique:division_units,division_name,' . $division->id,
        ]);

        $division->update($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully.');
    }

    public function destroyDivision(DivisionUnit $division): RedirectResponse{
        $division->delete();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully.');
    }

    public function showPosition(Position $position){
        $position->loadCount('users');

        return view('pages.admin.positions.show', compact('position'));
    }

    public function showDivision(DivisionUnit $division){
        $division->loadCount('users');

        return view('pages.admin.divisions.show', compact('division'));
    }
}
