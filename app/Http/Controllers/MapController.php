<?php

namespace App\Http\Controllers;

use App\Models\Panti;

class MapController extends Controller
{
    public function index()
    {
        return view('map.index');
    }

    public function data()
    {
        $pantis = Panti::verified()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with([
                'needs' => function ($query) {
                    $query->active()->with('category');
                },
            ])
            ->get();

        $data = $pantis->map(function (Panti $panti) {
            $activeNeeds = $panti->needs
                ->sortBy(function ($need) {
                    return array_search($need->priority, ['kritis', 'tinggi', 'sedang', 'rendah']);
                })
                ->values();

            $topNeed = $activeNeeds->first();
            [$latitude, $longitude] = $this->publicCoordinates($panti);

            return [
                'id' => $panti->id,
                'name' => $panti->name,
                'slug' => $panti->slug,
                'type' => $panti->type,
                'city' => $panti->city,
                'district' => $panti->district,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'urgency_status' => $panti->urgency_status,
                'urgency_score' => $panti->urgency_score,
                'active_needs_count' => $activeNeeds->count(),
                'top_need_title' => $topNeed?->title,
                'top_need_unit' => $topNeed?->unit,
                'detail_url' => url('/pantis/' . $panti->slug),
            ];
        });

        return response()->json($data->values());
    }

    private function publicCoordinates(Panti $panti): array
    {
        $latitude = (float) $panti->latitude;
        $longitude = (float) $panti->longitude;

        if ($panti->location_precision === Panti::LOCATION_APPROXIMATE) {
            return [round($latitude, 2), round($longitude, 2)];
        }

        return [$latitude, $longitude];
    }
}
