<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_must_verify_phone_before_entering_email(): void
    {
        $user = User::factory()->create([
            'phone' => '0801234567',
            'email' => null,
        ]);

        $response = $this->postWithCsrf(route('password.phone.verify'), [
            'phone' => $user->phone,
        ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('password_reset_phone', $user->phone);

        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Send Reset Link')
            ->assertSee($user->phone);
    }

    public function test_verified_phone_can_receive_a_password_reset_link_by_email(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'phone' => '0801234567',
            'email' => null,
        ]);

        $response = $this
            ->withSession([
                '_token' => 'test-token',
                'password_reset_phone' => $user->phone,
            ])
            ->post(route('password.email'), [
                '_token' => 'test-token',
                'email' => 'agent@example.com',
            ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionMissing('password_reset_phone');

        $user->refresh();

        $this->assertSame('agent@example.com', $user->email);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'agent@example.com',
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_can_complete_password_reset_with_valid_token(): void
    {
        $user = User::factory()->create([
            'phone' => '0801234567',
            'email' => 'agent@example.com',
            'password' => 'old-password',
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->postWithCsrf(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('pages.login'));

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
        $this->assertFalse(Auth::validate([
            'phone' => $user->phone,
            'password' => 'old-password',
        ]));
        $this->assertTrue(Auth::validate([
            'phone' => $user->phone,
            'password' => 'new-password',
        ]));
    }

    public function test_password_reset_clears_existing_database_sessions_for_the_user(): void
    {
        config(['session.driver' => 'database']);

        $user = User::factory()->create([
            'phone' => '0801234567',
            'email' => 'agent@example.com',
            'password' => 'old-password',
        ]);

        DB::table('sessions')->insert([
            'id' => 'session-one',
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => 'payload',
            'last_activity' => now()->timestamp,
        ]);

        $token = Password::broker()->createToken($user);

        $this->postWithCsrf(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('pages.login'));

        $this->assertDatabaseMissing('sessions', [
            'id' => 'session-one',
        ]);
    }

    public function test_password_reset_links_expire_after_thirty_minutes(): void
    {
        $this->assertSame(30, config('auth.passwords.users.expire'));
    }

    private function postWithCsrf(string $uri, array $data = [])
    {
        return $this
            ->withSession(['_token' => 'test-token'])
            ->post($uri, array_merge(['_token' => 'test-token'], $data));
    }
}
