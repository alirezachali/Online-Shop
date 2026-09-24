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
            'products' => ['sometimes', 'array'],
            'products.*.source_id' => ['required', 'integer'],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.barcode' => ['nullable', 'string', 'max:50'],
            'products.*.category_source_id' => ['nullable', 'integer'],
            'products.*.sell_price' => ['required', 'numeric', 'min:0'],
            'products.*.stock' => ['sometimes', 'numeric', 'min:0'],
            'products.*.unit' => ['sometimes', 'string', 'max:20'],
            'products.*.is_active' => ['sometimes', 'boolean'],
            'settings' => ['sometimes', 'array'],
            'settings.store_name' => ['sometimes', 'nullable', 'string', 'max:150'],
            'settings.phone' => ['sometimes', 'nullable', 'string', 'max:32'],
            'settings.mobile' => ['sometimes', 'nullable', 'string', 'max:32'],
            'settings.address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'settings.website' => ['sometimes', 'nullable', 'string', 'max:255'],
            'settings.currency' => ['sometimes', 'nullable', 'string', 'max:32'],
            'settings.receipt_footer' => ['sometimes', 'nullable', 'string', 'max:500'],
            'settings.tax_rate' => ['sometimes', 'nullable', 'string', 'max:16'],
            'settings.city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'settings.store_logo_url' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'settings.store_logo_base64' => ['sometimes', 'nullable', 'string'],
        ]);

        $sync->ingest(
            $data['categories'] ?? [],
            $data['products'] ?? [],
            $data['settings'] ?? [],
        );

        return response()->json(['ok' => true]);
    }
}
