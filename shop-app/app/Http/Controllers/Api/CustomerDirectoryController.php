<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerDirectoryController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $since = $request->query('since');

        $users = User::query()
            ->whereNotNull('phone')
            ->when($since, fn ($q) => $q->where('updated_at', '>=', $since))
            ->orderBy('id')
            ->get(['id', 'name', 'phone', 'city', 'address', 'created_at', 'updated_at']);

        return response()->json([
            'data' => $users,
        ]);
    }
}
