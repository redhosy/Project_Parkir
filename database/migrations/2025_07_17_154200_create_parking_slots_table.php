<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: A01, B15
            $table->enum('type', ['motor', 'mobil', 'truk']);
            $table->decimal('tarif', 8, 2);
            $table->enum('area', ['A', 'B', 'C']); // <--- PASTIKAN BARIS INI ADA
            $table->string('location_description')->nullable();
            $table->enum('status', ['available', 'booked', 'occupied', 'maintenance'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_slots');
    }
};
