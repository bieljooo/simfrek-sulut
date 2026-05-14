<?php

namespace App\Http\Controllers;

use App\Models\SpektrumFrekuensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SpectrumApiController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Cache::remember(SpektrumFrekuensi::MAP_CACHE_KEY, now()->addMinutes(10), fn () => SpektrumFrekuensi::query()
            ->sulawesi()
            ->mapDataset()
            ->orderBy('CLNT_NAME')
            ->get()
            ->map(fn (SpektrumFrekuensi $item) => $item->toMapPayload())
            ->filter(fn (array $item) => $item['lat'] !== null && $item['lng'] !== null)
            ->values()
            ->all());

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function statistics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => SpektrumFrekuensi::statistics(),
        ]);
    }
}
