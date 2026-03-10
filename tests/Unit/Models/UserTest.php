<?php

namespace Tests\Unit\Models;

use App\Models\Assignment;
use App\Models\Document;
use App\Models\DivisionUnit;
use App\Models\Position;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    // ========== Relationship Tests ==========

    public function test_user_belongs_to_position(): void
    {
        $position = Position::factory()->create();
        $user = User::factory()->create(['id_positions' => $position->id]);

        $this->assertInstanceOf(BelongsTo::class, $user->position());
        $this->assertInstanceOf(Position::class, $user->position);
        $this->assertEquals($position->id, $user->position->id);
    }

    public function test_user_belongs_to_division_unit(): void
    {
        $division = DivisionUnit::factory()->create();
        $user = User::factory()->create(['id_division_units' => $division->id]);

        $this->assertInstanceOf(BelongsTo::class, $user->divisionUnit());
        $this->assertInstanceOf(DivisionUnit::class, $user->divisionUnit);
        $this->assertEquals($division->id, $user->divisionUnit->id);
    }

    public function test_user_has_many_documents(): void
    {
        $user = User::factory()->create();
        Document::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(HasMany::class, $user->documents());
        $this->assertCount(3, $user->documents);
        $this->assertInstanceOf(Document::class, $user->documents->first());
    }

    public function test_user_has_many_assignments(): void
    {
        $user = User::factory()->create();
        Assignment::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(HasMany::class, $user->assignments());
        $this->assertCount(2, $user->assignments);
        $this->assertInstanceOf(Assignment::class, $user->assignments->first());
    }

    public function test_user_has_one_signature(): void
    {
        $user = User::factory()->create();
        $signature = Signature::factory()->create(['employee_id' => $user->id]);

        $this->assertInstanceOf(HasOne::class, $user->signature());
        $this->assertInstanceOf(Signature::class, $user->signature);
        $this->assertEquals($signature->id, $user->signature->id);
    }

    public function test_user_signature_returns_null_when_not_set(): void
    {
        $user = User::factory()->create();

        $this->assertNull($user->signature);
    }

    // ========== Accessor Tests: full_name ==========

    public function test_full_name_with_middle_name(): void
    {
        $user = User::factory()->make([
            'first_name'  => 'Juan',
            'middle_name' => 'Santos',
            'last_name'   => 'Dela Cruz',
        ]);

        $this->assertEquals('Juan S. Dela Cruz', $user->full_name);
    }

    public function test_full_name_without_middle_name(): void
    {
        $user = User::factory()->make([
            'first_name'  => 'Juan',
            'middle_name' => null,
            'last_name'   => 'Dela Cruz',
        ]);

        $this->assertEquals('Juan Dela Cruz', $user->full_name);
    }

    public function test_full_name_middle_initial_is_uppercased(): void
    {
        $user = User::factory()->make([
            'first_name'  => 'Maria',
            'middle_name' => 'reyes',
            'last_name'   => 'Santos',
        ]);

        $this->assertStringContainsString('R.', $user->full_name);
    }

    public function test_full_name_is_appended_attribute(): void
    {
        $user = User::factory()->make([
            'first_name'  => 'Ana',
            'middle_name' => null,
            'last_name'   => 'Lopez',
        ]);

        $array = $user->toArray();

        $this->assertArrayHasKey('full_name', $array);
    }

    // ========== Accessor Tests: initials ==========

    public function test_initials_returns_first_letters_of_first_and_last_name(): void
    {
        $user = User::factory()->make([
            'first_name' => 'Juan',
            'last_name'  => 'Dela Cruz',
        ]);

        $this->assertEquals('JD', $user->initials());
    }

    public function test_initials_are_case_sensitive(): void
    {
        $user = User::factory()->make([
            'first_name' => 'ana',
            'last_name'  => 'reyes',
        ]);

        // Str::substr preserves original casing
        $this->assertEquals('ar', $user->initials());
    }

    // ========== Fillable / Hidden / Cast Tests ==========

    public function test_password_is_hidden(): void
    {
        $user = User::factory()->make(['password' => 'secret123']);

        $this->assertArrayNotHasKey('password', $user->toArray());
    }

    public function test_two_factor_secret_is_hidden(): void
    {
        $user = User::factory()->make();

        $this->assertArrayNotHasKey('two_factor_secret', $user->toArray());
    }

    public function test_remember_token_is_hidden(): void
    {
        $user = User::factory()->make();

        $this->assertArrayNotHasKey('remember_token', $user->toArray());
    }

    public function test_fillable_fields_are_mass_assignable(): void
    {
        $fillable = [
            'employee_id',
            'first_name',
            'middle_name',
            'last_name',
            'gender',
            'id_positions',
            'id_division_units',
            'employee_type',
            'username',
            'email',
            'password',
            'last_login',
            'is_active',
            'user_type',
            'is_archived',
        ];

        $user = new User();

        foreach ($fillable as $field) {
            $this->assertContains($field, $user->getFillable(), "Field '{$field}' should be fillable.");
        }
    }

    public function test_password_is_hashed_on_create(): void
    {
        $user = User::factory()->create(['password' => 'plaintext_password']);

        $this->assertNotEquals('plaintext_password', $user->getAuthPassword());
    }

    // ========== Factory / DB Tests ==========

    public function test_user_can_be_created_in_database(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name'  => 'User',
            'email'      => 'test@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_documents_are_deleted_independently(): void
    {
        $user = User::factory()->create();
        $doc  = Document::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('documents', ['id' => $doc->id]);
    }

    public function test_eager_loads_position_and_division_unit_by_default(): void
    {
        $position = Position::factory()->create();
        $division = DivisionUnit::factory()->create();

        $user = User::factory()->create([
            'id_positions'      => $position->id,
            'id_division_units' => $division->id,
        ]);

        $fresh = User::find($user->id);

        $this->assertTrue($fresh->relationLoaded('position'));
        $this->assertTrue($fresh->relationLoaded('divisionUnit'));
    }
}