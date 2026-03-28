<?php

namespace App\Support;

use App\Services\CredentialService;
use Illuminate\Support\Facades\Log;

final class PaystackCredentials
{
    /**
     * Resolve secret key for the given mode (true = live, false = test).
     *
     * Priority for each key: DB (encrypted) → .env / config()
     */
    public static function secretForMode(bool $live): string
    {
        if ($live) {
            $secret = CredentialService::get(
                'paystack_live_secret',
                config('services.paystack.secret')
            );

            if (filled($secret)) {
                return (string) $secret;
            }

            Log::channel('paystack')->warning(
                'Live Paystack enabled but no live secret found in DB or .env; falling back to test secret.'
            );

            return (string) CredentialService::get(
                'paystack_test_secret',
                config('services.paystack.test_secret', '')
            );
        }

        return (string) CredentialService::get(
            'paystack_test_secret',
            config('services.paystack.test_secret', '')
        );
    }

    /**
     * Resolve public key for the given mode.
     */
    public static function publicForMode(bool $live): string
    {
        if ($live) {
            $public = CredentialService::get(
                'paystack_live_public',
                config('services.paystack.public')
            );

            if (filled($public)) {
                return (string) $public;
            }
        }

        return (string) CredentialService::get(
            'paystack_test_public',
            config('services.paystack.test_public', '')
        );
    }
}
