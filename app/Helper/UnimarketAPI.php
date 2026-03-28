<?php

namespace App\Helper;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnimarketAPI
{
    private static function getUrl(string $endpoint): string
    {
        $baseUrl = rtrim((string) config("services.unimarket.base_url"), "/");

        return $baseUrl . "/" . ltrim($endpoint, "/");
    }

    private static function getApiKey(): string
    {
        return (string) config("services.unimarket.key");
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function logResponse(string $action, Response $response, array $context = []): void
    {
        $level = $response->successful() ? "info" : "warning";
        if ($response->serverError()) {
            $level = "error";
        }

        Log::channel("unimarket")->{$level}("Unimarket API {$action}", array_merge($context, [
            "http_status" => $response->status(),
            "successful" => $response->successful(),
        ]));
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private static function finish(string $action, Response $response, array $context = []): array
    {
        self::logResponse($action, $response, $context);
        try {
            $response->throw();
        } catch (\Throwable $e) {
            Log::channel("unimarket")->error("Unimarket API {$action} request failed", [
                "message" => $e->getMessage(),
                "http_status" => $response->status(),
            ]);
            throw $e;
        }

        return $response->json();
    }

    public static function generateKey(string $email, string $password): array
    {
        $response = Http::post(self::getUrl("developer/generate-key"), [
            "email" => $email,
            "password" => $password,
        ]);

        return self::finish("generate_key", $response, ["email" => $email]);
    }

    public static function getWalletBalance(): array
    {
        $response = Http::withHeaders(["x-api-key" => self::getApiKey()])
            ->get(self::getUrl("developer/wallet/balance"));

        return self::finish("wallet_balance", $response);
    }

    public static function purchaseBundle(string $networkKey, string $recipient, string $capacity): array
    {
        $response = Http::withHeaders(["x-api-key" => self::getApiKey()])
            ->post(self::getUrl("developer/purchase"), [
                "networkKey" => $networkKey,
                "recipient" => $recipient,
                "capacity" => $capacity,
            ]);

        return self::finish("purchase", $response, [
            "network_key" => $networkKey,
            "capacity" => $capacity,
            "recipient_last4" => Str::substr(preg_replace("/\D/", "", $recipient) ?? "", -4),
        ]);
    }

    public static function getOrderStatus(string $reference): array
    {
        $endpoint = "developer/orders/" . urlencode($reference);

        $response = Http::withHeaders(["x-api-key" => self::getApiKey()])
            ->get(self::getUrl($endpoint));

        return self::finish("order_status", $response, [
            "reference_prefix" => Str::limit($reference, 16, ""),
        ]);
    }

    public static function getTransactionHistory(int $page = 1, int $limit = 20): array
    {
        $response = Http::withHeaders(["x-api-key" => self::getApiKey()])
            ->get(self::getUrl("developer/transactions"), [
                "page" => $page,
                "limit" => $limit,
            ]);

        return self::finish("transactions", $response, [
            "page" => $page,
            "limit" => $limit,
        ]);
    }
}
