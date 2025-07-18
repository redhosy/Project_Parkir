@extends('layouts.app')

@section('title', 'Selamat Datang di SmartPark')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 rounded-xl shadow-2xl">
    <div class="max-w-4xl w-full space-y-8 text-center">
        <div>
            <h1 class="text-5xl sm:text-6xl font-extrabold text-gradient mb-4 leading-tight">
                Parkir Lebih Mudah, Hidup Lebih Cerdas
            </h1>
            <p class="mt-4 text-xl text-gray-600">
                Pesan slot parkir Anda sekarang dan nikmati pengalaman parkir tanpa repot.
                Akses cepat, aman, dan efisien.
            </p>
        </div>
        <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="{{ route('booking.create') }}" class="btn-modern flex items-center justify-center">
                <i class="fas fa-calendar-check me-2"></i> Booking Sekarang
            </a>
            <a href="{{ route('booking.history') }}" class="btn-outline-modern flex items-center justify-center">
                <i class="fas fa-history me-2"></i> Riwayat Booking
            </a>
        </div>
        <div class="mt-12 text-gray-500 text-lg">
            <p>"Temukan slot parkir ideal Anda dalam hitungan detik."</p>
        </div>
    </div>
</div>

<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-gradient mb-12">Bagaimana Cara Kerjanya?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-lg text-center transform hover:scale-105 transition duration-300">
                <i class="fas fa-car-side text-5xl text-blue-500 mb-4"></i>
                <h3 class="text-2xl font-semibold text-gray-800 mb-3">1. Pilih Slot Anda</h3>
                <p class="text-gray-600">Pilih jenis kendaraan dan slot parkir yang tersedia dengan mudah.</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-lg text-center transform hover:scale-105 transition duration-300">
                <i class="fas fa-qrcode text-5xl text-purple-500 mb-4"></i>
                <h3 class="text-2xl font-semibold text-gray-800 mb-3">2. Dapatkan QR Code</h3>
                <p class="text-gray-600">Setelah booking, QR Code unik akan langsung muncul untuk Anda.</p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-lg text-center transform hover:scale-105 transition duration-300">
                <i class="fas fa-door-open text-5xl text-green-500 mb-4"></i>
                <h3 class="text-2xl font-semibold text-gray-800 mb-3">3. Scan & Parkir</h3>
                <p class="text-gray-600">Tunjukkan QR Code di portal, scan, dan masuk ke slot Anda.</p>
            </div>
        </div>
    </div>
</section>
@endsection