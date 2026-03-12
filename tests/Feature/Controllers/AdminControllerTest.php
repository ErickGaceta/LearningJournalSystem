<?php

namespace Tests\Feature\Controllers;

use App\Models\Assignment;
use App\Models\DivisionUnit;
use App\Models\Position;
use App\Models\TrainingModule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['user_type' => 'admin']);
    }

    // ========== Dashboard ==========

    public function test_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewIs('pages.admin.dashboard');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_passes_correct_variables(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertViewHasAll([
            'modules',
            'totalModules',
            'activeModules',
            'completedModules',
            'activeAssignments',
        ]);
    }

    public function test_dashboard_counts_completed_modules(): void
    {
        TrainingModule::factory()->create([
            'datestart' => now()->subMonth(),
            'dateend'   => now()->subDay(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertViewHas('completedModules', 1);
    }

    public function test_dashboard_counts_active_modules(): void
    {
        TrainingModule::factory()->create([
            'datestart' => now()->subDay(),
            'dateend'   => now()->addDay(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertViewHas('activeModules', 1);
    }

    // ========== Users Index ==========

    public function test_admin_can_view_users_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertViewIs('pages.admin.users.index');
    }

    public function test_users_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_users_index_passes_correct_data(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertViewHasAll([
            'usersActive',
            'usersArchived',
            'admins',
            'positions',
            'divisions',
        ]);
    }

    public function test_users_index_separates_active_and_archived(): void
    {
        User::factory()->create(['user_type' => 'user', 'is_archived' => 0]);
        User::factory()->create(['user_type' => 'user', 'is_archived' => 1]);

        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $this->assertCount(1, $response->viewData('usersActive'));
        $this->assertCount(1, $response->viewData('usersArchived'));
    }

    // ========== Store User ==========

    public function test_admin_can_create_user(): void
    {
        $position = Position::factory()->create();
        $division = DivisionUnit::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'employee_id'       => 'EMP-001',
                'first_name'        => 'Jane',
                'middle_name'       => null,
                'last_name'         => 'Doe',
                'gender'            => 'Female',
                'id_positions'      => $position->id,
                'id_division_units' => $division->id,
                'employee_type'     => 'Regular',
                'username'          => 'janedoe',
                'email'             => 'jane@example.com',
                'user_type'         => 'user',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }

    public function test_store_user_requires_unique_employee_id(): void
    {
        User::factory()->create(['employee_id' => 'EMP-001']);
        $position = Position::factory()->create();
        $division = DivisionUnit::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'employee_id'       => 'EMP-001',
                'first_name'        => 'Test',
                'last_name'         => 'User',
                'gender'            => 'Male',
                'id_positions'      => $position->id,
                'id_division_units' => $division->id,
                'employee_type'     => 'Regular',
                'username'          => 'testuser',
                'email'             => 'new@example.com',
                'user_type'         => 'user',
            ]);

        $response->assertSessionHasErrors(['employee_id']);
    }

    public function test_store_user_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);
        $position = Position::factory()->create();
        $division = DivisionUnit::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'employee_id'       => 'EMP-002',
                'first_name'        => 'Test',
                'last_name'         => 'User',
                'gender'            => 'Male',
                'id_positions'      => $position->id,
                'id_division_units' => $division->id,
                'employee_type'     => 'Regular',
                'username'          => 'newuser',
                'email'             => 'taken@example.com',
                'user_type'         => 'user',
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_store_user_generates_temporary_password(): void
    {
        $position = Position::factory()->create();
        $division = DivisionUnit::factory()->create();

        $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'employee_id'       => 'EMP-003',
                'first_name'        => 'Temp',
                'last_name'         => 'User',
                'gender'            => 'Male',
                'id_positions'      => $position->id,
                'id_division_units' => $division->id,
                'employee_type'     => 'Regular',
                'username'          => 'tempuser',
                'email'             => 'temp@example.com',
                'user_type'         => 'user',
            ]);

        $user = User::where('email', 'temp@example.com')->first();
        $this->assertNotNull($user->password);
    }

    // ========== Reset Password ==========

    public function test_admin_can_reset_user_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.reset-password', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertFalse(Hash::check('oldpassword', $user->fresh()->getAuthPassword()));
    }

    // ========== Update User ==========

    public function test_admin_can_update_user(): void
    {
        $user     = User::factory()->create(['user_type' => 'user']);
        $position = Position::factory()->create();
        $division = DivisionUnit::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.users.update', $user), [
                'employee_id'       => $user->employee_id,
                'first_name'        => 'Updated',
                'last_name'         => 'Name',
                'gender'            => 'Male',
                'id_positions'      => $position->id,
                'id_division_units' => $division->id,
                'employee_type'     => 'Regular',
                'username'          => $user->username,
                'email'             => $user->email,
                'user_type'         => 'user',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'first_name' => 'Updated']);
    }

    public function test_update_user_does_not_change_password_when_empty(): void
    {
        $user     = User::factory()->create(['password' => Hash::make('original')]);
        $position = Position::factory()->create();
        $division = DivisionUnit::factory()->create();

        $this->actingAs($this->admin)
            ->put(route('admin.users.update', $user), [
                'employee_id'       => $user->employee_id,
                'first_name'        => $user->first_name,
                'last_name'         => $user->last_name,
                'gender'            => $user->gender,
                'id_positions'      => $position->id,
                'id_division_units' => $division->id,
                'employee_type'     => $user->employee_type,
                'username'          => $user->username,
                'email'             => $user->email,
                'user_type'         => $user->user_type,
                'password'          => '',
            ]);

        $this->assertTrue(Hash::check('original', $user->fresh()->getAuthPassword()));
    }

    // ========== Archive / Restore User ==========

    public function test_admin_can_archive_user(): void
    {
        $user = User::factory()->create(['user_type' => 'user', 'is_archived' => 0]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.archive', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_archived' => 1]);
    }

    public function test_admin_cannot_archive_own_account(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.archive', $this->admin));

        $response->assertSessionHasErrors(['error']);
        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'is_archived' => 0]);
    }

    public function test_admin_can_restore_user(): void
    {
        $user = User::factory()->create(['user_type' => 'user', 'is_archived' => 1]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.users.restore', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_archived' => 0]);
    }

    // ========== Destroy User ==========

    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->create(['user_type' => 'user']);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin));

        $response->assertSessionHasErrors(['error']);
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    // ========== Positions ==========

    public function test_admin_can_view_positions_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.positions.index'));

        $response->assertOk();
        $response->assertViewIs('pages.admin.positions.index');
    }

    public function test_admin_can_create_position(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.positions.store'), [
                'positions' => 'Software Engineer',
            ]);

        $response->assertRedirect(route('admin.positions.index'));
        $this->assertDatabaseHas('positions', ['positions' => 'Software Engineer']);
    }

    public function test_store_position_requires_unique_name(): void
    {
        Position::factory()->create(['positions' => 'Manager']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.positions.store'), [
                'positions' => 'Manager',
            ]);

        $response->assertSessionHasErrors(['positions']);
    }

    public function test_admin_can_update_position(): void
    {
        $position = Position::factory()->create(['positions' => 'Old Title']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.positions.update', $position), [
                'positions' => 'New Title',
            ]);

        $response->assertRedirect(route('admin.positions.index'));
        $this->assertDatabaseHas('positions', ['id' => $position->id, 'positions' => 'New Title']);
    }

    public function test_admin_can_delete_position_without_users(): void
    {
        $position = Position::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.positions.destroy', $position));

        $response->assertRedirect(route('admin.positions.index'));
        $this->assertDatabaseMissing('positions', ['id' => $position->id]);
    }

    public function test_admin_cannot_delete_position_with_assigned_users(): void
    {
        $position = Position::factory()->create();
        User::factory()->create(['id_positions' => $position->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.positions.destroy', $position));

        $response->assertSessionHasErrors(['error']);
        $this->assertDatabaseHas('positions', ['id' => $position->id]);
    }

    // ========== Divisions ==========

    public function test_admin_can_view_divisions_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.divisions.index'));

        $response->assertOk();
        $response->assertViewIs('pages.admin.divisions.index');
    }

    public function test_admin_can_create_division(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.divisions.store'), [
                'division_units' => 'IT Department',
            ]);

        $response->assertRedirect(route('admin.divisions.index'));
        $this->assertDatabaseHas('division_units', ['division_units' => 'IT Department']);
    }

    public function test_store_division_requires_unique_name(): void
    {
        DivisionUnit::factory()->create(['division_units' => 'HR Department']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.divisions.store'), [
                'division_units' => 'HR Department',
            ]);

        $response->assertSessionHasErrors(['division_units']);
    }

    public function test_admin_can_update_division(): void
    {
        $division = DivisionUnit::factory()->create(['division_units' => 'Old Division']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.divisions.update', $division), [
                'division_units' => 'New Division',
            ]);

        $response->assertRedirect(route('admin.divisions.index'));
        $this->assertDatabaseHas('division_units', ['id' => $division->id, 'division_units' => 'New Division']);
    }

    public function test_admin_can_delete_division_without_users(): void
    {
        $division = DivisionUnit::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.divisions.destroy', $division));

        $response->assertRedirect(route('admin.divisions.index'));
        $this->assertDatabaseMissing('division_units', ['id' => $division->id]);
    }

    public function test_admin_cannot_delete_division_with_assigned_users(): void
    {
        $division = DivisionUnit::factory()->create();
        User::factory()->create(['id_division_units' => $division->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.divisions.destroy', $division));

        $response->assertSessionHasErrors(['error']);
        $this->assertDatabaseHas('division_units', ['id' => $division->id]);
    }
}