<?php

namespace App\Services;

use App\Models\Webhook;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Fire all active webhooks for a given event.
     */
    public function fire(string $event, array $payload): void
    {
        $webhooks = Webhook::where('is_active', true)->get()
            ->filter(fn ($w) => $w->shouldFireFor($event));

        foreach ($webhooks as $webhook) {
            $this->dispatch($webhook, $event, $payload);
        }
    }

    protected function dispatch(Webhook $webhook, string $event, array $payload): void
    {
        $start = microtime(true);

        $body = [
            'event'     => $event,
            'payload'   => $payload,
            'timestamp' => now()->toIso8601String(),
        ];

        $headers = ['Content-Type' => 'application/json'];
        if ($webhook->secret) {
            $signature = hash_hmac('sha256', json_encode($body), $webhook->secret);
            $headers['X-Webhook-Signature'] = $signature;
        }

        try {
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->post($webhook->url, $body);

            $duration = (int) ((microtime(true) - $start) * 1000);
            $success = $response->successful();

            WebhookLog::create([
                'webhook_id'      => $webhook->id,
                'event'           => $event,
                'payload'         => $payload,
                'response_status' => $response->status(),
                'response_body'   => mb_substr($response->body(), 0, 1000),
                'success'         => $success,
                'duration_ms'     => $duration,
            ]);

            $webhook->update([
                'last_triggered_at' => now(),
                'failure_count'     => $success ? 0 : $webhook->failure_count + 1,
            ]);

            if ($webhook->failure_count >= 10) {
                $webhook->update(['is_active' => false]);
                Log::warning("Webhook disabled after 10 consecutive failures: {$webhook->name}");
            }
        } catch (\Exception $e) {
            $duration = (int) ((microtime(true) - $start) * 1000);

            WebhookLog::create([
                'webhook_id'      => $webhook->id,
                'event'           => $event,
                'payload'         => $payload,
                'response_body'   => $e->getMessage(),
                'success'         => false,
                'duration_ms'     => $duration,
            ]);

            $webhook->increment('failure_count');
            Log::warning("Webhook failed: {$webhook->name} - {$e->getMessage()}");
        }
    }

    /**
     * Available webhook events.
     */
    public static function availableEvents(): array
    {
        return [
            'deal.stage_changed',
            'deal.won',
            'deal.lost',
            'contact.funnel_changed',
            'contact.created',
            'form.submitted',
            'ticket.created',
            'ticket.resolved',
            'appointment.created',
            'article.published',
            'page.published',
        ];
    }
}
