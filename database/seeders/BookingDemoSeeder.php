<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingDemoSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $unitId = DB::table('measurement_units')->where('value', 'sq. ft.')->where('is_deleted', 0)->value('id');
        if (!$unitId) {
            $unitId = DB::table('measurement_units')->insertGetId([
                'value' => 'sq. ft.',
                'status' => 1,
                'is_deleted' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $sizes = [
            ['10 sq. ft.', '5', '2', '8'],
            ['30 sq. ft.', '6', '5', '8'],
            ['50 sq. ft.', '10', '5', '8'],
            ['100 sq. ft.', '10', '10', '8'],
            ['200 sq. ft.', '20', '10', '8'],
            ['300 sq. ft.', '20', '15', '8'],
        ];
        $sizeIds = [];
        foreach ($sizes as $size) {
            $id = DB::table('storage_unit_sizes')->where('unit_type_name', $size[0])->where('is_deleted', 0)->value('id');
            if (!$id) {
                $id = DB::table('storage_unit_sizes')->insertGetId([
                    'unit_type_name' => $size[0],
                    'measurement_unit' => $unitId,
                    'width' => $size[1],
                    'length' => $size[2],
                    'height' => $size[3],
                    'no_of_units' => '4',
                    'status' => 1,
                    'is_deleted' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $sizeIds[$size[0]] = $id;
        }

        $countryId = DB::table('countries')->where('name', 'UAE')->where('is_deleted', 0)->value('id');
        if (!$countryId) {
            $hasDefault = DB::table('countries')->where('is_deleted', 0)->where('is_default', 1)->exists();
            $countryId = DB::table('countries')->insertGetId([
                'name' => 'UAE',
                'status' => 1,
                'is_deleted' => 0,
                'is_default' => $hasDefault ? 0 : 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $cities = [
            'Sharjah' => true,
            'Dubai' => false,
        ];
        $cityIds = [];
        $cityHasDefault = DB::table('cities')->where('country_id', $countryId)->where('is_deleted', 0)->where('is_default', 1)->exists();
        foreach ($cities as $name => $preferDefault) {
            $id = DB::table('cities')->where('city_name', $name)->where('country_id', $countryId)->where('is_deleted', 0)->value('id');
            if (!$id) {
                $id = DB::table('cities')->insertGetId([
                    'country_id' => $countryId,
                    'city_name' => $name,
                    'status' => 1,
                    'is_deleted' => 0,
                    'is_default' => ($preferDefault && !$cityHasDefault) ? 1 : 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                if ($preferDefault && !$cityHasDefault) {
                    $cityHasDefault = true;
                }
            }
            $cityIds[$name] = $id;
        }

        $areas = [
            ['Sharjah', 'Industrial Area', '25.3463', '55.4209', true],
            ['Dubai', 'Al Quoz', '25.1412', '55.2300', true],
        ];
        $areaIds = [];
        foreach ($areas as $area) {
            $id = DB::table('locations')
                ->where('loc_name', $area[1])
                ->where('city_id', $cityIds[$area[0]])
                ->where('is_deleted', 0)
                ->value('id');
            if (!$id) {
                $areaHasDefault = DB::table('locations')->where('city_id', $cityIds[$area[0]])->where('is_deleted', 0)->where('is_default', 1)->exists();
                $id = DB::table('locations')->insertGetId([
                    'loc_name' => $area[1],
                    'country_id' => $countryId,
                    'city_id' => $cityIds[$area[0]],
                    'lat' => $area[2],
                    'lang' => $area[3],
                    'status' => 1,
                    'is_deleted' => 0,
                    'is_default' => ($area[4] && !$areaHasDefault) ? 1 : 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $areaIds[$area[0]] = $id;
        }

        $warehouses = [];
        foreach (['Sharjah' => 'Sharjah Storage', 'Dubai' => 'Dubai Storage'] as $city => $name) {
            $id = DB::table('warehouses')->where('name', $name)->where('is_deleted', 0)->value('id');
            if (!$id) {
                $id = DB::table('warehouses')->insertGetId([
                    'name' => $name,
                    'loc_id' => $areaIds[$city],
                    'description' => 'Sample facility for booking tests.',
                    'status' => 1,
                    'is_deleted' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $warehouses[$city] = $id;
        }

        $levelIds = [];
        foreach (['Ground' => 'Ground floor units', 'Upper' => 'Upper level units'] as $name => $description) {
            $id = DB::table('storage_unit_levels')->where('name', $name)->where('is_deleted', 0)->value('id');
            if (!$id) {
                $id = DB::table('storage_unit_levels')->insertGetId([
                    'name' => $name,
                    'description' => $description,
                    'status' => 1,
                    'is_deleted' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $levelIds[$name] = $id;
        }

        $typeIds = [];
        foreach (['Personal' => 'Personal storage', 'Business' => 'Business storage'] as $name => $description) {
            $id = DB::table('storage_types')->where('name', $name)->where('is_deleted', 0)->value('id');
            if (!$id) {
                $id = DB::table('storage_types')->insertGetId([
                    'name' => $name,
                    'description' => $description,
                    'status' => 1,
                    'is_deleted' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $typeIds[$name] = $id;
        }

        $samples = [
            ['SK-SHJ-10', 'Sharjah', '10 sq. ft.', 'Ground', 'Personal', 180],
            ['SK-SHJ-30', 'Sharjah', '30 sq. ft.', 'Ground', 'Personal', 280],
            ['SK-SHJ-50', 'Sharjah', '50 sq. ft.', 'Ground', 'Personal', 420],
            ['SK-SHJ-100', 'Sharjah', '100 sq. ft.', 'Upper', 'Business', 760],
            ['SK-DXB-50', 'Dubai', '50 sq. ft.', 'Ground', 'Personal', 480],
            ['SK-DXB-200', 'Dubai', '200 sq. ft.', 'Upper', 'Business', 1400],
            ['SK-DXB-300', 'Dubai', '300 sq. ft.', 'Ground', 'Business', 1900],
        ];

        foreach ($samples as $sample) {
            $exists = DB::table('storage_units')->where('storage_unit_name', $sample[0])->where('is_deleted', 0)->exists();
            if ($exists) {
                continue;
            }
            DB::table('storage_units')->insert([
                'storage_unit_name' => $sample[0],
                'wh_id' => $warehouses[$sample[1]],
                'stype_id' => $typeIds[$sample[4]],
                'slevel_id' => $levelIds[$sample[3]],
                'ssize_id' => $sizeIds[$sample[2]],
                'price' => $sample[5],
                'location' => $sample[1],
                'width' => '0',
                'length' => '0',
                'height' => '0',
                'status' => 'vacant',
                'is_maintenance' => 0,
                'is_deleted' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
