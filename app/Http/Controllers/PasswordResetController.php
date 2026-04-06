<?php

namespace App\Http\Controllers;

use App\Http\Customs\CustomHelper;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function showForgotPasswordForm(Request $request): View
    {
        if ($request->boolean('change_phone')) {
            $request->session()->forget('password_reset_phone');
        }

        return view('pages.forgot-password', [
            'verifiedPhone' => $request->session()->get('password_reset_phone'),
        ]);
    }

    public function verifyPhone(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'phone' => ['required', 'digits:10'],
        ]);

        $user = User::where('phone', $validatedData['phone'])->first();

        if (! $user) {
            return back()
                ->withErrors(['phone' => 'We could not find an account with that phone number.'])
                ->withInput();
        }

        $request->session()->put('password_reset_phone', $user->phone);
        CustomHelper::message('primary', 'Phone number verified. Enter the email address that should receive your 30-minute reset link.');

        return redirect()->route('password.request');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $verifiedPhone = $request->session()->get('password_reset_phone');

        if (! $verifiedPhone) {
            CustomHelper::message('danger', 'Start by verifying the phone number used to register this account.');

            return redirect()->route('password.request');
        }

        $user = User::where('phone', $verifiedPhone)->first();

        if (! $user) {
            $request->session()->forget('password_reset_phone');
            CustomHelper::message('danger', 'We could not verify that phone number. Please try again.');

            return redirect()->route('password.request');
        }

        $validatedData = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->forceFill([
            'email' => Str::lower($validatedData['email']),
        ])->save();

        $status = Password::sendResetLink([
            'email' => $user->email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            return back()
                ->withErrors(['email' => __($status)])
                ->withInput();
        }

        $request->session()->forget('password_reset_phone');
        CustomHelper::message('primary', 'Password reset email sent successfully. The link will expire in 30 minutes.');

        return redirect()->route('password.request');
    }

    public function showResetPasswordForm(Request $request, string $token): View
    {
        return view('pages.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $validatedData,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                $this->clearUserSessions($user);

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withErrors(['email' => __($status)])
                ->withInput($request->only('email'));
        }

        CustomHelper::message('primary', 'Your password has been reset successfully. Please sign in with your new password.');

        return redirect()->route('pages.login');
    }

    private function clearUserSessions(User $user): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        $sessionTable = config('session.table', 'sessions');

        if (! Schema::hasTable($sessionTable)) {
            return;
        }

        DB::table($sessionTable)
            ->where('user_id', $user->getKey())
            ->delete();
    }
}
