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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Data Pemesan
            $table->string('nama_pemesan');
            $table->string('no_hp');
            $table->string('email')->nullable();

            // Data Kendaraan
            $table->string('merk_kendaraan');
            $table->string('warna_kendaraan');
            $table->string('license_plate');
            $table->enum('jenis_kendaraan', ['motor', 'mobil', 'truk']);

            // Relasi dengan Slot Parkir
            $table->foreignId('parking_slot_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            // QR Code
            $table->string('qr_code')->unique();

            // Waktu
            $table->timestamp('booking_time');
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();

            // Status
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('pending');
            $table->integer('usage_count')->default(0)->comment('Maksimal 2: masuk dan keluar');

            // Tambahkan kolom check_in_count dan check_out_count
            $table->integer('check_in_count')->default(0);  
            $table->integer('check_out_count')->default(0);

            // Pembayaran
            $table->decimal('total_payment', 12, 2)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
