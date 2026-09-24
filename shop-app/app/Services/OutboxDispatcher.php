<?php

namespace App\Services;

use App\Models\OutboxMessage;
use Illuminate\Support\Facades\Http;

class OutboxDispatcher
{
    public function dispatchPending(int $limit = 50): int
    {
        $sent = 0;

        $messages = OutboxMessage::query()
            ->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('available_at')->orWhere('available_at', '<=', now());
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        foreach ($messages as $message) {
            if ($this->send($message)) {
                $sent++;
            }
        }

        return $sent;
    }

    public function send(OutboxMessage $message): bool
    {
        $url = (string) config('integration.broker_url');
        $token = (string) config('integration.token');

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->withToken($token)
                ->post($url, [
                    'type' => $message->type,
                    'idempotency_key' => $message->idempotency_key,
                    'payload' => $message->payload,
                ]);

            if ($response->successful()) {
                $message->markSent();

                return true;
            }

            $message->markFailed('HTTP '.$response->status().' '.$response->body());
        } catch (\Throwable $e) {
            $message->markFailed($e->getMessage());
        }

        return false;
    }
}
