<?php

namespace App\Services;

use App\Models\ApiCredential;
use Illuminate\Support\Facades\Cache;

/**
 * Central resolver for sensitive API credentials.
 *
 * Priority: DB (encrypted) → .env / config() fallback → null
 *
 * Results are cached for 5 minutes. Call flush() after any write.
 */
final class CredentialService
{
    private const CACHE_KEY = 'api_credentials_map';
    private const CACHE_TTL = 300; // seconds

    /**
     * Resolve a credential value by its key_name.
     *
     * @param  string       $keyName   e.g. 'paystack_live_secret'
     * @param  string|null  $fallback  Config/env fallback (already resolved by caller)
     */
    public static function get(string $keyName, ?string $fallback = null): ?string
    {
        $map = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return ApiCredential::all()
                ->mapWithKeys(fn (ApiCredential $c) => [$c->key_name => $c->value])
                ->toArray();
        });

        $value = $map[$keyName] ?? null;

        return filled($value) ? (string) $value : $fallback;
    }

    /**
     * Bust the in-memory credentials cache after a write.
     */
    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Canonical list of all managed credential slots.
     * Key: key_name, Value: [label, group, is_secret]
     */
    public static function definedKeys(): array
    {
        return [
            'paystack_test_public'  => ['label' => 'Test Public Key',        'group' => 'paystack',  'is_secret' => true],
            'paystack_test_secret'  => ['label' => 'Test Secret Key',        'group' => 'paystack',  'is_secret' => true],
            'paystack_live_public'  => ['label' => 'Live Public Key',        'group' => 'paystack',  'is_secret' => true],
            'paystack_live_secret'  => ['label' => 'Live Secret Key',        'group' => 'paystack',  'is_secret' => true],
            'realest_api_key'       => ['label' => 'Realest / DataHub API Key', 'group' => 'external', 'is_secret' => true],
            'realest_base_url'      => ['label' => 'Realest API Base URL',   'group' => 'external',  'is_secret' => false],
            'pai_key'               => ['label' => 'PAI Key',                'group' => 'external',  'is_secret' => true],
        ];
    }

    /**
     * Ensure all defined credential slots exist in the DB (with null values).
     * Safe to call repeatedly; uses updateOrCreate.
     */
    public static function ensureSlots(): void
    {
        foreach (self::definedKeys() as $keyName => $meta) {
            ApiCredential::firstOrCreate(
                ['key_name' => $keyName],
                [
                    'key_label' => $meta['label'],
                    'key_group' => $meta['group'],
                    'is_secret' => $meta['is_secret'],
                    'value'     => null,
                ]
            );
        }
    }
}
