<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RealestApiService
{
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
            if (isset($payload["message"]) && is_string($payload["message"])) {
                $payload["message"] = $this->safeUserFacingMessage($payload["message"]);
            }

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
            "message" => $this->safeUserFacingMessage($response->body()),
        ];
    }

    /**
     * Never surface raw HTML (e.g. provider 404 pages) to flash messages or the UI.
     */
    private function safeUserFacingMessage(?string $text): string
    {
        $fallback = "Unexpected response from the provider API. Check configuration or try again.";
        if ($text === null || $text === "") {
            return $fallback;
        }
        $trimmed = trim($text);
        if ($trimmed === "") {
            return $fallback;
        }
        $lower = strtolower($trimmed);
        if (
            str_starts_with($trimmed, "<")
            || str_contains($lower, "<!doctype")
            || str_contains($lower, "<html")
        ) {
            return $fallback;
        }
        if (strlen($trimmed) > 280) {
            return $fallback;
        }

        return $trimmed;
    }
}
