<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CatalogSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogIngestController extends Controller
{
    public function __invoke(Request $request, CatalogSyncService $sync): JsonResponse
    {
        $data = $request->validate([
            'categories' => ['sometimes', 'array'],
            'categories.*.source_id' => ['required', 'integer'],
            'categories.*.name' => ['required', 'string', 'max:255'],
            'categories.*.is_active' => ['sometimes', 'boolean'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.source_id' => ['required', 'integer'],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.barcode' => ['nullable', 'string', 'max:50'],
            'products.*.category_source_id' => ['nullable', 'integer'],
            'products.*.sell_price' => ['required', 'numeric', 'min:0'],
            'products.*.stock' => ['sometimes', 'numeric', 'min:0'],
            'products.*.unit' => ['sometimes', 'string', 'max:20'],
            'products.*.is_active' => ['sometimes', 'boolean'],
        ]);

        $sync->ingest($data['categories'] ?? [], $data['products']);

        return response()->json(['ok' => true]);
    }
}
