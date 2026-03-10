<?php

namespace Tests\Feature\Controllers;

use App\Models\Assignment;
use App\Models\Document;
use App\Models\DivisionUnit;
use App\Models\Position;
use App\Models\Signature;
use App\Models\TrainingModule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Tests\TestCase;

class UserControllerTest extends FrameworkTestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['user_type' => 'user']);
    }

    // ========== Dashboard ==========

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.dashboard'));

        $response->assertOk();
        $response->assertViewIs('pages.user.dashboard');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('user.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_passes_correct_variables_to_view(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.dashboard'));

        $response->assertViewHasAll([
            'activeAssignments',
            'completedAssignments',
            'myDocuments',
            'user',
            'trainings',
            'userTrainings',
        ]);
    }

    public function test_dashboard_counts_active_assignments(): void
    {
        $activeModule = TrainingModule::factory()->create([
            'datestart' => now()->subDay(),
            'dateend'   => now()->addDay(),
        ]);
        Assignment::factory()->create([
            'user_id'   => $this->user->id,
            'module_id' => $activeModule->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('user.dashboard'));

        $response->assertViewHas('activeAssignments', 1);
    }

    public function test_dashboard_counts_completed_assignments(): void
    {
        $completedModule = TrainingModule::factory()->create([
            'datestart' => now()->subMonth(),
            'dateend'   => now()->subDay(),
        ]);
        Assignment::factory()->create([
            'user_id'   => $this->user->id,
            'module_id' => $completedModule->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('user.dashboard'));

        $response->assertViewHas('completedAssignments', 1);
    }

    public function test_dashboard_counts_my_documents(): void
    {
        Document::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('user.dashboard'));

        $response->assertViewHas('myDocuments', 3);
    }

    public function test_dashboard_only_shows_own_assignments(): void
    {
        $otherUser = User::factory()->create();
        $module = TrainingModule::factory()->create([
            'datestart' => now()->subDay(),
            'dateend'   => now()->addDay(),
        ]);
        Assignment::factory()->create(['user_id' => $otherUser->id, 'module_id' => $module->id]);

        $response = $this->actingAs($this->user)->get(route('user.dashboard'));

        $response->assertViewHas('activeAssignments', 0);
    }

    // ========== Upload Signature ==========

    public function test_user_can_upload_signature(): void
    {
        $base64 = 'data:image/png;base64,' . base64_encode(str_repeat('a', 100));

        $response = $this->actingAs($this->user)
            ->post(route('user.signature.upload'), [
                'signature_base64' => $base64,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Signature uploaded successfully!');
    }

    public function test_upload_signature_requires_base64_field(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('user.signature.upload'), []);

        $response->assertSessionHasErrors(['signature_base64']);
    }

    public function test_upload_signature_creates_signature_record(): void
    {
        $base64 = 'data:image/png;base64,' . base64_encode(str_repeat('a', 100));

        $this->actingAs($this->user)
            ->post(route('user.signature.upload'), [
                'signature_base64' => $base64,
            ]);

        $this->assertDatabaseHas('signatures', ['employee_id' => $this->user->id]);
    }

    public function test_upload_signature_replaces_existing_signature(): void
    {
        Signature::factory()->create(['employee_id' => $this->user->id]);

        $base64 = 'data:image/png;base64,' . base64_encode(str_repeat('b', 100));

        $this->actingAs($this->user)
            ->post(route('user.signature.upload'), [
                'signature_base64' => $base64,
            ]);

        $this->assertDatabaseCount('signatures', 1);
    }

    public function test_upload_signature_requires_authentication(): void
    {
        $response = $this->post(route('user.signature.upload'), [
            'signature_base64' => 'data:image/png;base64,abc',
        ]);

        $response->assertRedirect(route('login'));
    }

    // ========== My Trainings ==========

    public function test_user_can_view_my_trainings(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.trainings.index'));

        $response->assertOk();
        $response->assertViewIs('pages.user.trainings.index');
    }

    public function test_my_trainings_requires_authentication(): void
    {
        $response = $this->get(route('user.trainings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_my_trainings_only_shows_own_trainings(): void
    {
        $otherUser = User::factory()->create();
        $module    = TrainingModule::factory()->create();

        Assignment::factory()->create(['user_id' => $this->user->id, 'module_id' => $module->id]);
        Assignment::factory()->create(['user_id' => $otherUser->id, 'module_id' => $module->id]);

        $response = $this->actingAs($this->user)->get(route('user.trainings.index'));

        $trainings = $response->viewData('trainings');
        $this->assertCount(1, $trainings);
        $this->assertEquals($this->user->id, $trainings->first()->user_id);
    }

    public function test_my_trainings_passes_user_to_view(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.trainings.index'));

        $response->assertViewHas('user', $this->user);
    }

    // ========== Edit Profile ==========

    public function test_user_can_view_edit_profile(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.profile.edit'));

        $response->assertOk();
        $response->assertViewIs('pages.user.profile.edit');
    }

    public function test_edit_profile_passes_positions_and_divisions(): void
    {
        Position::factory()->count(2)->create();
        DivisionUnit::factory()->count(2)->create();

        $response = $this->actingAs($this->user)->get(route('user.profile.edit'));

        $response->assertViewHasAll(['positions', 'divisions']);
    }

    public function test_edit_profile_requires_authentication(): void
    {
        $response = $this->get(route('user.profile.edit'));

        $response->assertRedirect(route('login'));
    }

    // ========== Update Profile ==========

    public function test_user_can_update_profile(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('user.profile.update'), [
                'first_name'  => 'Updated',
                'middle_name' => 'M',
                'last_name'   => 'User',
                'gender'      => 'Male',
                'email'       => 'updated@example.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully!');
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com', 'first_name' => 'Updated']);
    }

    public function test_update_profile_requires_first_name(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('user.profile.update'), [
                'first_name' => '',
                'last_name'  => 'User',
                'gender'     => 'Male',
                'email'      => 'test@example.com',
            ]);

        $response->assertSessionHasErrors(['first_name']);
    }

    public function test_update_profile_requires_valid_gender(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('user.profile.update'), [
                'first_name' => 'Test',
                'last_name'  => 'User',
                'gender'     => 'Other',
                'email'      => 'test@example.com',
            ]);

        $response->assertSessionHasErrors(['gender']);
    }

    public function test_update_profile_email_must_be_unique_excluding_self(): void
    {
        $other = User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->actingAs($this->user)
            ->put(route('user.profile.update'), [
                'first_name' => 'Test',
                'last_name'  => 'User',
                'gender'     => 'Male',
                'email'      => 'taken@example.com',
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_can_keep_own_email_on_update(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('user.profile.update'), [
                'first_name' => 'Test',
                'last_name'  => 'User',
                'gender'     => 'Male',
                'email'      => $this->user->email,
            ]);

        $response->assertSessionMissing('errors');
        $response->assertSessionHas('success');
    }

    public function test_update_profile_can_change_password(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('user.profile.update'), [
                'first_name'       => 'Test',
                'last_name'        => 'User',
                'gender'           => 'Male',
                'email'            => $this->user->email,
                'current_password' => 'password',
                'password'         => 'newpassword1',
                'password_confirmation' => 'newpassword1',
            ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('newpassword1', $this->user->fresh()->getAuthPassword()));
    }

    public function test_update_profile_rejects_wrong_current_password(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('user.profile.update'), [
                'first_name'       => 'Test',
                'last_name'        => 'User',
                'gender'           => 'Male',
                'email'            => $this->user->email,
                'current_password' => 'wrongpassword',
                'password'         => 'newpassword1',
                'password_confirmation' => 'newpassword1',
            ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_update_profile_requires_authentication(): void
    {
        $response = $this->put(route('user.profile.update'), []);

        $response->assertRedirect(route('login'));
    }
}