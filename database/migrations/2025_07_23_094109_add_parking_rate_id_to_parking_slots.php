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
        Schema::table('parking_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('parking_rate_id')->nullable()->after('type');
            $table->foreign('parking_rate_id')->references('id')->on('parking_rates')->onDelete('set null');
            
            // Remove the old tarif column since we're now using the relationship
            $table->dropColumn('tarif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_slots', function (Blueprint $table) {
            $table->decimal('tarif', 8, 2)->after('type')->nullable();
            $table->dropForeign(['parking_rate_id']);
            $table->dropColumn('parking_rate_id');
        });
    }
};
