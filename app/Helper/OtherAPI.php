<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class OtherAPI
{
    /**
     * Creates a pre-configured HTTP client instance for API requests.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    private static function apiClient(): PendingRequest
    {
        // Get credentials from your .env file via the config
        $apiKey = config('services.other.key');
        $baseUrl = config('services.other.base_url');

        // Pre-configure the HTTP client with the base URL and default headers
        return Http::withHeaders([
            'X-API-Key' => $apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->baseUrl($baseUrl);
    }

    /**
     * Purchase a data bundle for a recipient.
     *
     * @param string $recipient The phone number to receive the data.
     * @param string $capacity The data package size (e.g., '1' for 1GB).
     * @param string $networkKey The network identifier (e.g., 'YELLO' for MTN).
     * @return array|null The API response as an associative array.
     */
    public static function purchaseData(string $recipient, string $capacity, string $networkKey): ?array
    {
        $response = self::apiClient()->post('/api/external/data-purchase', [
            'networkKey' => $networkKey,
            'recipient' => $recipient,
            'capacity' => $capacity,
        ]);

        return $response->json();
    }

    /**
     * Check the status of a previously placed order by its reference.
     *
     * @param string $reference The unique order reference number.
     * @return array|null The API response as an associative array.
     */
    public static function checkOrderStatus(string $reference): ?array
    {
        $response = self::apiClient()->get('/api/external/order-status', [
            'reference' => $reference,
        ]);

        return $response->json();
    }
}
