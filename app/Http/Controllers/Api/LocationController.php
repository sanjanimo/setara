<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function provinces()
    {
        return Province::orderBy('name')->get(['id', 'name']);
    }

    public function cities(Request $request)
    {
        $request->validate(['province_id' => 'required|integer']);

        // Coba ambil dari DB dulu (cepat)
        $cities = City::where('province_id', $request->province_id)->get();

        if ($cities->isNotEmpty()) {
            return $cities->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'type' => $c->type]);
        }

        return Cache::remember(
            "location:cities:{$request->province_id}",
            now()->addDay(),
            fn () => $this->fetchExternal("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$request->province_id}.json")
        );
    }

    public function districts(Request $request)
    {
        $request->validate(['city_id' => 'required|integer']);

        $districts = District::where('city_id', $request->city_id)->get();

        if ($districts->isNotEmpty()) {
            return $districts->map(fn ($d) => ['id' => $d->id, 'name' => $d->name]);
        }

        return Cache::remember(
            "location:districts:{$request->city_id}",
            now()->addDay(),
            fn () => $this->fetchExternal("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$request->city_id}.json")
        );
    }

    private function fetchExternal(string $url): array
    {
        try {
            $response = Http::connectTimeout(2)
                ->timeout(5)
                ->get($url);

            return $response->successful() && is_array($response->json())
                ? $response->json()
                : [];
        } catch (\Throwable) {
            return [];
        }
    }
}
