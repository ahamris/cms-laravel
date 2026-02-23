<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerfexCrmService
{
    /**
     * Send a lead to Perfex CRM via REST API (POST /api/v1/leads).
     * Returns the API response array on success, null when disabled or on failure.
     *
     * @param  array{name: string, email: string, company?: string, title?: string, note?: string, source?: string, status?: int, assigned?: int}  $payload
     * @return array<string, mixed>|null
     */
    public function sendLead(array $payload): ?array
    {
        if (! $this->isEnabled()) {
            return null;
        }

        $url = config('perfex.base_url').'/api/v1/leads';
        $apiKey = config('perfex.api_key');

        if (empty($apiKey)) {
            Log::warning('Perfex CRM: API key not set, skipping lead sync.');

            return null;
        }

        $body = [
            'name' => $payload['name'] ?? 'Unknown',
            'email' => $payload['email'] ?? '',
            'company' => $payload['company'] ?? '',
            'title' => $payload['title'] ?? null,
            'source' => $payload['source'] ?? config('perfex.default_lead_source'),
            'status' => $payload['status'] ?? config('perfex.default_lead_status'),
            'assigned' => $payload['assigned'] ?? config('perfex.default_assigned'),
        ];

        if (! empty($payload['note'])) {
            $body['description'] = $payload['note'];
        }

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15)->post($url, $body);

            if ($response->successful()) {
                Log::info('Perfex CRM: Lead sent successfully.', ['email' => $body['email']]);

                return $response->json();
            }

            Log::warning('Perfex CRM: Lead sync failed.', [
                'status' => $response->status(),
                'body' => $response->body(),
                'email' => $body['email'],
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::error('Perfex CRM: Exception while sending lead.', [
                'message' => $e->getMessage(),
                'email' => $payload['email'] ?? null,
            ]);

            return null;
        }
    }

    public function isEnabled(): bool
    {
        return (bool) config('perfex.enabled');
    }

    /**
     * Whether Perfex is set up and should receive leads (enabled + base URL + API key).
     * Use this before dispatching the queue job so the job is only queued when Perfex is configured.
     */
    public function isConfigured(): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }
        $baseUrl = config('perfex.base_url');
        $apiKey = config('perfex.api_key');

        return ! empty($baseUrl) && ! empty($apiKey);
    }
}
