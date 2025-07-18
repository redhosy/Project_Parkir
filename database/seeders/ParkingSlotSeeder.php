<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingSlot;

class ParkingSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        ParkingSlot::truncate();

        $slotData = [];

        for ($i = 1; $i <= 50; $i++) {
            $zone = chr(65 + rand(0, 2)); // Menghasilkan 'A', 'B', atau 'C'
            $number = str_pad($i, 2, '0', STR_PAD_LEFT); // Menghasilkan '01', '02', ..., '50'

            $type = 'motor';
            $tarif = rand(2000, 3000); // Tarif motor

            $randomType = rand(1, 10);
            if ($randomType <= 5) { // 50% motor
                $type = 'motor';
                $tarif = rand(2000, 3000);
            } elseif ($randomType <= 8) { // 30% mobil
                $type = 'mobil';
                $tarif = rand(5000, 7000);
            } else { // 20% truk
                $type = 'truk';
                $tarif = rand(8000, 12000);
            }

            $slotData[] = [
                'code' => $zone . $number, // Contoh: A01, B15
                'type' => $type,
                'tarif' => $tarif,
                'area' => $zone, // Zona sama dengan huruf kode
                'status' => 'available', // Status awal selalu tersedia
                'location_description' => $this->generateLocationDescription($zone, $number),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Memasukkan data dalam batch untuk performa lebih baik
        foreach (array_chunk($slotData, 10) as $chunk) {
            ParkingSlot::insert($chunk);
        }
    }

    /**
     * Menghasilkan deskripsi lokasi acak untuk slot parkir.
     * @param string $zone
     * @param string $number
     * @return string
     */
    protected function generateLocationDescription($zone, $number)
    {
        $zoneNames = [
            'A' => 'Area Motor',
            'B' => 'Area Mobil',
            'C' => 'Area Truk'
        ];

        $locations = [
            'dekat pintu masuk',
            'dekat toilet',
            'dekat lift',
            'dekat tangga darurat',
            'tengah area',
            'dekat pos satpam',
            'sudut bangunan',
            'dekat taman',
            'area khusus',
            'dekat loket pembayaran'
        ];

        return sprintf(
            "Slot %s%s, %s, %s",
            $zone,
            $number,
            $zoneNames[$zone] ?? 'Area Umum', // Fallback jika zona tidak dikenal
            $locations[array_rand($locations)] // Pilih lokasi acak
        );
    }
}
