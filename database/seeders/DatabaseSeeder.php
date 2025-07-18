<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
  use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menonaktifkan pemeriksaan foreign key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            UserAndAdminSeeder::class,
            // Pastikan seeder yang melakukan truncate pada tabel yang direferensikan (misal: parking_slots)
            // dipanggil SETELAH seeder yang melakukan truncate pada tabel yang mereferensikan (misal: bookings)
            // Atau, cukup nonaktifkan foreign key checks seperti di atas.
            ParkingSlotSeeder::class,
            // Jika Anda memiliki seeder lain yang juga melakukan truncate pada tabel yang saling mereferensikan,
            // pastikan semua panggilan $this->call() berada di antara SET FOREIGN_KEY_CHECKS=0 dan SET FOREIGN_KEY_CHECKS=1.
        ]);

        // Mengaktifkan kembali pemeriksaan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
