<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutboxMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OutboxAckController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'idempotency_keys' => ['required', 'array', 'min:1'],
            'idempotency_keys.*' => ['required', 'string'],
        ]);

        $messages = OutboxMessage::query()
            ->whereIn('idempotency_key', $data['idempotency_keys'])
            ->where('status', 'pending')
            ->get();

        foreach ($messages as $message) {
            $message->markSent();
        }

        return response()->json(['acked' => $messages->count()]);
    }
}
