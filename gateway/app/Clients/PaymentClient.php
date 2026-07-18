<?php

namespace App\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentClient
{
    public function __construct(
        private PendingRequest $pendingRequest,
    ) {}

    public function get(
        string $endpoint,
        array $query,
        array $headers = [],
    ): array {
        try {
            Log::info("GET request to " . $endpoint,
                [
                    'endpoint' => $endpoint,
                    'query' => $query,
                ]
            );

            $response = $this->pendingRequest
                ->withHeaders($this->makeRequestHeaders($headers))
                ->get($endpoint, $query);
            
            Log::info("GET response from " . $endpoint,
                [
                    'endpoint' => $endpoint,
                    'response' => $response->json(),
                    'status' => $response->status()
                ]
            );

            if ($response instanceof Throwable) {
                throw $response;
            }
            
            return $response->json();
        } catch (\Throwable $e) {
            Log::error("Error in GET request to " . $endpoint,
                [
                    'endpoint' => $endpoint,
                    'query' => $query,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            throw $e;
        }
    }

    public function post(
        string $endpoint,
        array $payload,
        array $headers = [],
    ): array {
        try {
            Log::info("POST request to " . $endpoint,
                [
                    'endpoint' => $endpoint,
                    'payload' => $payload,
                ]
            );

            $response = $this->pendingRequest
                ->withHeaders($this->makeRequestHeaders($headers))
                ->post($endpoint, $payload);

            Log::info("POST response from " . $endpoint,
                [
                    'endpoint' => $endpoint,
                    'response' => $response->json(),
                    'status' => $response->status()
                ]
            );
            
            if ($response instanceof Throwable) {
                throw $response;
            }
            
            return $response->json();
        } catch (\Throwable $e) {
            Log::error("Error in POST request to " . $endpoint,
                [
                    'endpoint' => $endpoint,
                    'payload' => $payload,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            throw $e;
        }
    }

    private function makeRequestHeaders(array $requestHeaders): array 
    {
        return array_merge($requestHeaders, [
            'Authorization' => 'Bearer ' . config('services.payment.auth')
        ]);
    }
}