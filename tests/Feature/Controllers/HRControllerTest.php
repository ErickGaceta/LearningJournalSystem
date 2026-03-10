<?php

namespace Tests\Feature\Controllers;

use App\Models\Assignment;
use App\Models\Document;
use App\Models\TrainingModule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HRControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $hr;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hr = User::factory()->create(['user_type' => 'hr']);
    }

    // ========== Dashboard ==========

    public function test_hr_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->hr)->get(route('hr.dashboard'));

        $response->assertOk();
        $response->assertViewIs('pages.hr.dashboard');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('hr.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_passes_correct_variables(): void
    {
        $response = $this->actingAs($this->hr)->get(route('hr.dashboard'));

        $response->assertViewHasAll([
            'modules',
            'totalModules',
            'activeTraining',
            'usersInTraining',
        ]);
    }

    public function test_dashboard_counts_active_training(): void
    {
        TrainingModule::factory()->create([
            'datestart' => now()->subDay(),
            'dateend'   => now()->addDay(),
        ]);
        TrainingModule::factory()->create([
            'datestart' => now()->subMonth(),
            'dateend'   => now()->subDay(),
        ]);

        $response = $this->actingAs($this->hr)->get(route('hr.dashboard'));

        $response->assertViewHas('activeTraining', 1);
    }

    public function test_dashboard_counts_users_in_training(): void
    {
        $module = TrainingModule::factory()->create([
            'datestart' => now()->subDay(),
            'dateend'   => now()->addDay(),
        ]);
        $users = User::factory()->count(3)->create(['user_type' => 'user']);
        foreach ($users as $user) {
            Assignment::factory()->create(['user_id' => $user->id, 'module_id' => $module->id]);
        }

        $response = $this->actingAs($this->hr)->get(route('hr.dashboard'));

        $response->assertViewHas('usersInTraining', 3);
    }

    // ========== Training Modules Index ==========

    public function test_hr_can_view_modules_index(): void
    {
        $response = $this->actingAs($this->hr)->get(route('hr.modules.index'));

        $response->assertOk();
        $response->assertViewIs('pages.hr.modules.index');
    }

    public function test_modules_index_requires_authentication(): void
    {
        $response = $this->get(route('hr.modules.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_modules_index_can_search_by_title(): void
    {
        TrainingModule::factory()->create(['title' => 'Leadership Training']);
        TrainingModule::factory()->create(['title' => 'Safety Seminar']);

        $response = $this->actingAs($this->hr)
            ->get(route('hr.modules.index', ['search' => 'Leadership']));

        $modules = $response->viewData('trainingModules');
        $this->assertCount(1, $modules);
        $this->assertEquals('Leadership Training', $modules->first()->title);
    }

    public function test_modules_index_can_search_by_venue(): void
    {
        TrainingModule::factory()->create(['venue' => 'Main Hall']);
        TrainingModule::factory()->create(['venue' => 'Conference Room']);

        $response = $this->actingAs($this->hr)
            ->get(route('hr.modules.index', ['search' => 'Main Hall']));

        $modules = $response->viewData('trainingModules');
        $this->assertCount(1, $modules);
    }

    public function test_modules_index_passes_users_of_type_user(): void
    {
        User::factory()->create(['user_type' => 'user']);
        User::factory()->create(['user_type' => 'admin']);

        $response = $this->actingAs($this->hr)->get(route('hr.modules.index'));

        $users = $response->viewData('users');
        $this->assertTrue($users->every(fn($u) => $u->user_type === 'user'));
    }

    // ========== Store Module ==========

    public function test_hr_can_create_training_module(): void
    {
        $response = $this->actingAs($this->hr)
            ->post(route('hr.modules.store'), [
                'title'            => 'New Module',
                'hours'            => '8',
                'datestart'        => now()->toDateString(),
                'dateend'          => now()->addDay()->toDateString(),
                'venue'            => 'Training Room',
                'conductedby'      => 'John Trainer',
                'registration_fee' => '500',
            ]);

        $response->assertRedirect(route('hr.modules.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('training_module', ['title' => 'New Module']);
    }

    public function test_store_module_requires_title(): void
    {
        $response = $this->actingAs($this->hr)
            ->post(route('hr.modules.store'), [
                'hours'       => '8',
                'datestart'   => now()->toDateString(),
                'dateend'     => now()->addDay()->toDateString(),
                'venue'       => 'Room',
                'conductedby' => 'Trainer',
            ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_store_module_requires_dateend_after_datestart(): void
    {
        $response = $this->actingAs($this->hr)
            ->post(route('hr.modules.store'), [
                'title'       => 'Module',
                'hours'       => '8',
                'datestart'   => now()->toDateString(),
                'dateend'     => now()->subDay()->toDateString(),
                'venue'       => 'Room',
                'conductedby' => 'Trainer',
            ]);

        $response->assertSessionHasErrors(['dateend']);
    }

    public function test_store_module_requires_authentication(): void
    {
        $response = $this->post(route('hr.modules.store'), []);

        $response->assertRedirect(route('login'));
    }

    // ========== Update Module ==========

    public function test_hr_can_update_training_module(): void
    {
        $module = TrainingModule::factory()->create();

        $response = $this->actingAs($this->hr)
            ->put(route('hr.modules.update', $module), [
                'title'       => 'Updated Title',
                'hours'       => '16',
                'datestart'   => now()->toDateString(),
                'dateend'     => now()->addWeek()->toDateString(),
                'venue'       => 'Updated Venue',
                'conductedby' => 'New Trainer',
            ]);

        $response->assertRedirect(route('hr.modules.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('training_module', ['id' => $module->id, 'title' => 'Updated Title']);
    }

    public function test_update_module_validates_required_fields(): void
    {
        $module = TrainingModule::factory()->create();

        $response = $this->actingAs($this->hr)
            ->put(route('hr.modules.update', $module), [
                'title' => '',
            ]);

        $response->assertSessionHasErrors(['title']);
    }

    // ========== Destroy Module ==========

    public function test_hr_can_delete_training_module(): void
    {
        $module = TrainingModule::factory()->create();

        $response = $this->actingAs($this->hr)
            ->delete(route('hr.modules.destroy', $module));

        $response->assertRedirect(route('hr.modules.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('training_module', ['id' => $module->id]);
    }

    public function test_destroy_module_requires_authentication(): void
    {
        $module = TrainingModule::factory()->create();

        $response = $this->delete(route('hr.modules.destroy', $module));

        $response->assertRedirect(route('login'));
    }

    // ========== Store Assignment ==========

    public function test_hr_can_assign_users_to_module(): void
    {
        $module = TrainingModule::factory()->create();
        $users  = User::factory()->count(2)->create(['user_type' => 'user']);

        $response = $this->actingAs($this->hr)
            ->post(route('hr.assignments.store'), [
                'module_id' => $module->id,
                'user_ids'  => $users->pluck('id')->toArray(),
            ]);

        $response->assertRedirect(route('hr.modules.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('assignments', 2);
    }

    public function test_store_assignment_does_not_duplicate_existing(): void
    {
        $module = TrainingModule::factory()->create();
        $user   = User::factory()->create(['user_type' => 'user']);
        Assignment::factory()->create(['user_id' => $user->id, 'module_id' => $module->id]);

        $this->actingAs($this->hr)
            ->post(route('hr.assignments.store'), [
                'module_id' => $module->id,
                'user_ids'  => [$user->id],
            ]);

        $this->assertDatabaseCount('assignments', 1);
    }

    public function test_store_assignment_requires_at_least_one_user(): void
    {
        $module = TrainingModule::factory()->create();

        $response = $this->actingAs($this->hr)
            ->post(route('hr.assignments.store'), [
                'module_id' => $module->id,
                'user_ids'  => [],
            ]);

        $response->assertSessionHasErrors(['user_ids']);
    }

    public function test_store_assignment_requires_valid_module(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->hr)
            ->post(route('hr.assignments.store'), [
                'module_id' => 9999,
                'user_ids'  => [$user->id],
            ]);

        $response->assertSessionHasErrors(['module_id']);
    }

    public function test_store_assignment_requires_valid_user_ids(): void
    {
        $module = TrainingModule::factory()->create();

        $response = $this->actingAs($this->hr)
            ->post(route('hr.assignments.store'), [
                'module_id' => $module->id,
                'user_ids'  => [99999],
            ]);

        $response->assertSessionHasErrors(['user_ids.0']);
    }

    // ========== Archive Document ==========

    public function test_hr_can_archive_a_document(): void
    {
        $document = Document::factory()->create(['isArchived' => false]);

        $response = $this->actingAs($this->hr)
            ->post(route('hr.documents.archive', $document));

        $response->assertRedirect(route('hr.modules.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('documents', ['id' => $document->id, 'isArchived' => true]);
    }

    public function test_archive_document_requires_authentication(): void
    {
        $document = Document::factory()->create();

        $response = $this->post(route('hr.documents.archive', $document));

        $response->assertRedirect(route('login'));
    }

    // ========== Preview Document ==========

    public function test_hr_can_preview_document_as_pdf(): void
    {
        $document = Document::factory()
            ->for(User::factory()->has(Signature::factory(), 'signature'))
            ->for(TrainingModule::factory(), 'module')
            ->create();

        $response = $this->actingAs($this->hr)
            ->get(route('hr.documents.preview', $document));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}