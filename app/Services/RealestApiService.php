<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RealestApiService
{
    public function checkBalance(): array
    {
        if (!$this->isConfigured()) {
            return $this->missingConfigurationResponse();
        }

        $response = $this->apiClient()->get("/check-balance");

        return $this->formatResponse($response);
    }

    public function purchaseBundle(string $network, string $recipient, string $size): array
    {
        if (!$this->isConfigured()) {
            return $this->missingConfigurationResponse();
        }

        $response = $this->apiClient()->post("/purchase", [
            "network" => $network,
            "size" => $size,
            "phone_number" => $recipient,
        ]);

        return $this->formatResponse($response);
    }

    public function getOrderStatus(string $orderCode): array
    {
        if (!$this->isConfigured()) {
            return $this->missingConfigurationResponse();
        }

        $response = $this->apiClient()->get("/order-status/" . urlencode($orderCode));

        return $this->formatResponse($response);
    }

    private function apiClient(): PendingRequest
    {
        return Http::acceptJson()
            ->asJson()
            ->withToken((string) config("services.realest.api_key"))
            ->baseUrl(rtrim((string) config("services.realest.base_url"), "/"));
    }

    private function isConfigured(): bool
    {
        return filled(config("services.realest.base_url"))
            && filled(config("services.realest.api_key"));
    }

    private function missingConfigurationResponse(): array
    {
        return [
            "status" => "error",
            "message" => "Realest API credentials are not configured.",
        ];
    }

    private function formatResponse(Response $response): array
    {
        $payload = $response->json();

        if (is_array($payload)) {
            return $payload;
        }

        if ($response->successful()) {
            return [
                "status" => "success",
                "data" => [],
            ];
        }

        return [
            "status" => "error",
            "message" => $response->body() ?: "Unexpected response from Realest API.",
        ];
    }
}
