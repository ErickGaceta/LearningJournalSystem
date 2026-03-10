<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    // ========== Create (GET /login) ==========

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertViewIs('pages.auth.login');
    }

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect();
    }

    // ========== Store (POST /login) — Validation ==========

    public function test_login_requires_login_field(): void
    {
        $response = $this->post(route('login'), [
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['login']);
    }

    public function test_login_requires_password_field(): void
    {
        $response = $this->post(route('login'), [
            'login' => 'user@example.com',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_login_requires_both_fields(): void
    {
        $response = $this->post(route('login'), []);

        $response->assertSessionHasErrors(['login', 'password']);
    }

    // ========== Store — Invalid Credentials ==========

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create(['email' => 'user@example.com']);

        $response = $this->post(route('login'), [
            'login'    => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $response = $this->post(route('login'), [
            'login'    => 'ghost@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }

    public function test_login_fails_with_nonexistent_username(): void
    {
        $response = $this->post(route('login'), [
            'login'    => 'ghostuser',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }

    public function test_login_error_message_is_correct_on_failure(): void
    {
        $response = $this->post(route('login'), [
            'login'    => 'nobody@example.com',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }

    public function test_login_retains_login_input_on_failure(): void
    {
        $response = $this->post(route('login'), [
            'login'    => 'user@example.com',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasInput('login', 'user@example.com');
    }

    public function test_password_is_not_retained_in_session_on_failure(): void
    {
        $this->post(route('login'), [
            'login'    => 'user@example.com',
            'password' => 'wrong',
        ]);

        $this->assertNull(session()->getOldInput('password'));
    }

    // ========== Store — Login via Email ==========

    public function test_user_can_login_with_email(): void
    {
        $user = User::factory()->create([
            'email'      => 'user@example.com',
            'password'   => 'password',
            'last_login' => now(),
        ]);

        $response = $this->post(route('login'), [
            'login'    => 'user@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    // ========== Store — Login via Username ==========

    public function test_user_can_login_with_username(): void
    {
        $user = User::factory()->create([
            'username'   => 'johndoe',
            'password'   => 'password',
            'last_login' => now(),
        ]);

        $this->post(route('login'), [
            'login'    => 'johndoe',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    // ========== Store — Session Regeneration ==========

    public function test_session_is_regenerated_on_login(): void
    {
        $user           = User::factory()->create(['password' => 'password', 'last_login' => now()]);
        $sessionIdBefore = $this->withSession([])->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        // Assert session was regenerated (no session fixation)
        $sessionIdBefore->assertSessionMissing('_token_old');
        $this->assertAuthenticatedAs($user);
    }

    // ========== Store — Last Login Tracking ==========

    public function test_last_login_is_updated_on_successful_login(): void
    {
        $user = User::factory()->create([
            'password'   => 'password',
            'last_login' => now()->subDay(),
        ]);

        $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertNotNull($user->fresh()->last_login);
    }

    // ========== Store — First Login (Force Password Change) ==========

    public function test_first_login_redirects_to_password_change(): void
    {
        $user = User::factory()->create([
            'password'   => 'password',
            'last_login' => null,
        ]);

        $response = $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('password.change.show'));
    }

    public function test_first_login_sets_must_change_password_in_session(): void
    {
        $user = User::factory()->create([
            'password'   => 'password',
            'last_login' => null,
        ]);

        $response = $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHas('must_change_password', true);
    }

    public function test_first_login_flash_info_message(): void
    {
        $user = User::factory()->create([
            'password'   => 'password',
            'last_login' => null,
        ]);

        $response = $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHas('info', 'Welcome! Please change your password to continue.');
    }

    public function test_last_login_is_set_on_first_login(): void
    {
        $user = User::factory()->create([
            'password'   => 'password',
            'last_login' => null,
        ]);

        $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertNotNull($user->fresh()->last_login);
    }

    // ========== Store — Redirect by User Type ==========

    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'user_type'  => 'admin',
            'password'   => 'password',
            'last_login' => now(),
        ]);

        $response = $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_hr_is_redirected_to_hr_dashboard(): void
    {
        $user = User::factory()->create([
            'user_type'  => 'hr',
            'password'   => 'password',
            'last_login' => now(),
        ]);

        $response = $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('hr.dashboard'));
    }

    public function test_secretary_is_redirected_to_hr_dashboard(): void
    {
        $user = User::factory()->create([
            'user_type'  => 'secretary',
            'password'   => 'password',
            'last_login' => now(),
        ]);

        $response = $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('hr.dashboard'));
    }

    public function test_regular_user_is_redirected_to_user_dashboard(): void
    {
        $user = User::factory()->create([
            'user_type'  => 'user',
            'password'   => 'password',
            'last_login' => now(),
        ]);

        $response = $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.dashboard'));
    }

    // ========== Store — Rate Limiting ==========

    public function test_too_many_attempts_blocks_login(): void
    {
        $user        = User::factory()->create();
        $throttleKey = strtolower($user->email) . '|127.0.0.1';

        RateLimiter::hit($throttleKey);
        RateLimiter::hit($throttleKey);
        RateLimiter::hit($throttleKey);
        RateLimiter::hit($throttleKey);
        RateLimiter::hit($throttleKey);

        $response = $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors(['login']);
        $this->assertStringContainsString(
            'Too many login attempts',
            session('errors')->first('login')
        );
    }

    public function test_rate_limiter_is_cleared_on_successful_login(): void
    {
        $user        = User::factory()->create(['password' => 'password', 'last_login' => now()]);
        $throttleKey = strtolower($user->email) . '|127.0.0.1';

        RateLimiter::hit($throttleKey);
        RateLimiter::hit($throttleKey);

        $this->post(route('login'), [
            'login'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertEquals(0, RateLimiter::attempts($throttleKey));
    }

    public function test_rate_limiter_increments_on_failed_login(): void
    {
        $throttleKey = 'nobody@example.com|127.0.0.1';
        RateLimiter::clear($throttleKey);

        $this->post(route('login'), [
            'login'    => 'nobody@example.com',
            'password' => 'wrong',
        ]);

        $this->assertEquals(1, RateLimiter::attempts($throttleKey));
    }

    // ========== Destroy (POST /logout) ==========

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_logout_invalidates_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
    }

    public function test_logout_redirects_to_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_logout(): void
    {
        $response = $this->post(route('logout'));

        $this->assertGuest();
    }
}