<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate(); // Menggunakan truncate() untuk menghapus semua data dan mereset auto-increment ID

        // Data Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@smartpark.com',
            'password' => Hash::make('12345678'), // Password: 'password'
            'role' => 'admin', // Peran 'admin'
            'phone_number' => '081122334455',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data User Biasa (Customer)
        User::create([
            'name' => 'User Customer 1',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password'), // Password: 'password'
            'role' => 'customer', // Peran 'customer'
            'phone_number' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'name' => 'User Customer 2',
            'email' => 'customer2@example.com',
            'password' => Hash::make('password'), // Password: 'password'
            'role' => 'customer',
            'phone_number' => '089876543210',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Anda juga bisa menggunakan factory untuk membuat lebih banyak user dummy
        User::factory(5)->create([
            'role' => 'customer', // Pastikan factory membuat user dengan role 'customer'
        ]);
    }
}
