<?php

namespace App\Services;

use App\Services\CredentialService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RealestApiService
{
    public function purchaseBundle(string $network, string $recipient, string $size): array
    {
        if (!$this->isConfigured()) {
            Log::channel("realest")->warning("Realest API purchase skipped: credentials not configured", [
                "network" => $network,
                "size" => $size,
            ]);

            return $this->missingConfigurationResponse();
        }

        $response = $this->apiClient()->post("/purchase", [
            "network" => $network,
            "size" => $size,
            "phone_number" => $recipient,
        ]);

        $this->logRealestExchange("purchase", "POST /purchase", $response, [
            "network" => $network,
            "size" => $size,
            "recipient_last4" => Str::substr(preg_replace("/\D/", "", $recipient) ?? "", -4),
        ]);

        return $this->formatResponse($response);
    }

    public function getOrderStatus(string $orderCode): array
    {
        if (!$this->isConfigured()) {
            Log::channel("realest")->warning("Realest API order-status skipped: credentials not configured", [
                "order_code_prefix" => Str::limit($orderCode, 16, ""),
            ]);

            return $this->missingConfigurationResponse();
        }

        $response = $this->apiClient()->get("/order-status/" . urlencode($orderCode));

        $this->logRealestExchange("order_status", "GET /order-status/*", $response, [
            "order_code_prefix" => Str::limit($orderCode, 16, ""),
        ]);

        return $this->formatResponse($response);
    }

    private function logRealestExchange(string $action, string $endpointLabel, Response $response, array $context = []): void
    {
        $payload = $response->json();
        $apiStatus = is_array($payload) ? ($payload["status"] ?? null) : null;
        $msg = is_array($payload) && isset($payload["message"]) && is_string($payload["message"])
            ? Str::limit($payload["message"], 240)
            : null;

        $level = $response->successful() ? "info" : "warning";
        if ($response->serverError()) {
            $level = "error";
        }

        Log::channel("realest")->{$level}("Realest API {$action}", array_merge($context, [
            "endpoint" => $endpointLabel,
            "http_status" => $response->status(),
            "api_status" => $apiStatus,
            "message" => $msg,
        ]));
    }

    private function apiClient(): PendingRequest
    {
        $apiKey  = CredentialService::get('realest_api_key', config('services.realest.api_key'));
        $baseUrl = CredentialService::get('realest_base_url', config('services.realest.base_url'));

        return Http::acceptJson()
            ->asJson()
            ->withToken((string) $apiKey)
            ->baseUrl(rtrim((string) $baseUrl, '/'));
    }

    private function isConfigured(): bool
    {
        $apiKey  = CredentialService::get('realest_api_key', config('services.realest.api_key'));
        $baseUrl = CredentialService::get('realest_base_url', config('services.realest.base_url'));

        return filled($baseUrl) && filled($apiKey);
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
