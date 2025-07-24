<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingRate;

class ParkingRateSeeder extends Seeder
{
    public function run()
    {
        // Reset table
        ParkingRate::truncate();

        // Default rates for motor
        ParkingRate::create([
            'vehicle_type' => 'motor',
            'duration_start_hour' => 0,
            'duration_end_hour' => 1,
            'rate' => 2000,
            'is_flat_rate' => false
        ]);

        ParkingRate::create([
            'vehicle_type' => 'motor',
            'duration_start_hour' => 1,
            'duration_end_hour' => null,
            'rate' => 1000,
            'is_flat_rate' => false
        ]);

        // Default rates for mobil
        ParkingRate::create([
            'vehicle_type' => 'mobil',
            'duration_start_hour' => 0,
            'duration_end_hour' => 1,
            'rate' => 5000,
            'is_flat_rate' => false
        ]);

        ParkingRate::create([
            'vehicle_type' => 'mobil',
            'duration_start_hour' => 1,
            'duration_end_hour' => null,
            'rate' => 3000,
            'is_flat_rate' => false
        ]);

        // Default rates for truk
        ParkingRate::create([
            'vehicle_type' => 'truk',
            'duration_start_hour' => 0,
            'duration_end_hour' => 1,
            'rate' => 10000,
            'is_flat_rate' => false
        ]);

        ParkingRate::create([
            'vehicle_type' => 'truk',
            'duration_start_hour' => 1,
            'duration_end_hour' => null,
            'rate' => 7000,
            'is_flat_rate' => false
        ]);

        // Daily flat rates
        ParkingRate::create([
            'vehicle_type' => 'motor',
            'duration_start_hour' => 24,
            'duration_end_hour' => null,
            'rate' => 20000,
            'is_flat_rate' => true
        ]);

        ParkingRate::create([
            'vehicle_type' => 'mobil',
            'duration_start_hour' => 24,
            'duration_end_hour' => null,
            'rate' => 50000,
            'is_flat_rate' => true
        ]);

        ParkingRate::create([
            'vehicle_type' => 'truk',
            'duration_start_hour' => 24,
            'duration_end_hour' => null,
            'rate' => 100000,
            'is_flat_rate' => true
        ]);
    }
}
