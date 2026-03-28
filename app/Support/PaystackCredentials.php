<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

final class PaystackCredentials
{
    /**
     * Resolve secret key for the given mode (true = live, false = test).
     */
    public static function secretForMode(bool $live): string
    {
        if ($live) {
            $secret = config("services.paystack.secret");
            if (filled($secret)) {
                return (string) $secret;
            }
            Log::channel("paystack")->warning("Live Paystack enabled but PAYSTACK_SECRET_KEY empty; falling back to test secret.");
            return (string) config("services.paystack.test_secret", "");
        }

        return (string) config("services.paystack.test_secret", "");
    }

    /**
     * Resolve public key for the given mode (e.g. future inline checkout).
     */
    public static function publicForMode(bool $live): string
    {
        if ($live) {
            $public = config("services.paystack.public");
            if (filled($public)) {
                return (string) $public;
            }
        }

        return (string) config("services.paystack.test_public", "");
    }
}
