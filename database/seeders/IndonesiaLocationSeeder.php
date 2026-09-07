<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class IndonesiaLocationSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')->json();

        foreach ($provinces as $p) {
            $province = Province::create(['id' => $p['id'], 'name' => $p['name'], 'code' => substr($p['id'], 0, 2)]);

            $cities = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$p['id']}.json")->json();
            foreach ($cities as $c) {
                $type = str_starts_with($c['name'], 'KOTA') ? 'KOTA' : 'KABUPATEN';

                $city = City::create([
                    'id'          => $c['id'],
                    'province_id' => $p['id'],
                    'name'        => $c['name'],
                    'type'        => $type
                ]);

                $targetCities = [
                    'KOTA BANDUNG',
                    'KOTA JAKARTA SELATAN',
                    'KOTA JAKARTA PUSAT',
                    'KOTA JAKARTA BARAT',
                    'KOTA JAKARTA TIMUR',
                    'KOTA JAKARTA UTARA',
                    'KOTA SURABAYA',
                    'KOTA YOGYAKARTA',
                    'KOTA SEMARANG'
                ];

                if (in_array($c['name'], $targetCities)) {
                    $districts = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$c['id']}.json")->json();
                    foreach ($districts as $d) {
                        District::create([
                            'id'      => $d['id'],
                            'city_id' => $c['id'],
                            'name'    => $d['name']
                        ]);
                    }
                }
            }
        }
    }
}
