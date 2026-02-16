<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\DivisionUnit;
use App\Models\Position;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        $division = DivisionUnit::create(['name' => 'IT']);
        $position = Position::create(['name' => 'Developer']);

        $response = $this->post('/register', [
            'employee_id' => 'EMP001',
            'fname' => 'John',
            'lname' => 'Doe',
            'gender' => 'Male',
            'division' => $division->id,
            'position' => $position->id,
            'username' => 'johndoe',
            'user_type' => 'employee',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }
}
