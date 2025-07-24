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
        Schema::create('parking_rates', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type'); // motor, mobil, truk
            $table->integer('duration_start_hour'); // Jam mulai untuk tarif ini
            $table->integer('duration_end_hour')->nullable(); // Jam akhir untuk tarif ini (null untuk seterusnya)
            $table->decimal('rate', 10, 2); // Tarif per jam dalam periode ini
            $table->boolean('is_flat_rate')->default(false); // Apakah tarif flat atau per jam
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_rates');
    }
};
