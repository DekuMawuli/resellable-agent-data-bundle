<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class UnimarketAPI
{
    /**
     * A private helper method to build the full URL for an endpoint.
     *
     * @param string $endpoint
     * @return string
     */
    private static function getUrl(string $endpoint): string
    {
        // Use the config helper to get the base URL
        $baseUrl = rtrim(config('services.unimarket.base_url'), '/');
        return $baseUrl . '/' . ltrim($endpoint, '/');
    }

    /**
     * A private helper to get the configured API key.
     *
     * @return string
     */
    private static function getApiKey(): string
    {
        return config('services.unimarket.key');
    }

    /**
     * Generate an API key for authentication.
     * (This method is an exception as it doesn't use the API key)
     */
    public static function generateKey(string $email, string $password): array
    {
        $response = Http::post(self::getUrl('developer/generate-key'), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->throw();
        return $response->json();
    }

    /**
     * Get the current wallet balance.
     */
    public static function getWalletBalance(): array
    {
        $response = Http::withHeaders(['x-api-key' => self::getApiKey()])
            ->get(self::getUrl('developer/wallet/balance'));

        $response->throw();
        return $response->json();
    }

    /**
     * Purchase a data bundle for a recipient.
     */
    public static function purchaseBundle(string $networkKey, string $recipient, string $capacity): array
    {
        $response = Http::withHeaders(['x-api-key' => self::getApiKey()])
            ->post(self::getUrl('developer/purchase'), [
                'networkKey' => $networkKey,
                'recipient' => $recipient,
                'capacity' => $capacity,
            ]);

        $response->throw();
        return $response->json();
    }

    /**
     * Check the status of an order using its reference.
     */
    public static function getOrderStatus(string $reference): array
    {
        $endpoint = 'developer/orders/' . urlencode($reference);

        $response = Http::withHeaders(['x-api-key' => self::getApiKey()])
            ->get(self::getUrl($endpoint));

        $response->throw();
        return $response->json();
    }

    /**
     * Get transaction history with pagination.
     */
    public static function getTransactionHistory(int $page = 1, int $limit = 20): array
    {
        $response = Http::withHeaders(['x-api-key' => self::getApiKey()])
            ->get(self::getUrl('developer/transactions'), [
                'page' => $page,
                'limit' => $limit,
            ]);

        $response->throw();
        return $response->json();
    }
}
