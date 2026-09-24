<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OutboxMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OutboxPullController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $limit = min(100, max(1, (int) $request->integer('limit', 50)));

        $messages = OutboxMessage::query()
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->get(['id', 'type', 'idempotency_key', 'payload', 'created_at']);

        return response()->json(['data' => $messages]);
    }
}
