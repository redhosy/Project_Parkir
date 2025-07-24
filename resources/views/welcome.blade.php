@extends('layouts.app')

@section('title', 'Selamat Datang di SmartPark')

@section('content')
<section class="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">
    <div class="container mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="text-center lg:text-left fade-in">
            <h1 class="text-5xl lg:text-7xl font-extrabold text-gradient mb-6 leading-tight">
                Parkir Lebih Mudah, Hidup Lebih Cerdas
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                Pesan slot parkir Anda sekarang dan nikmati pengalaman parkir tanpa repot.
                Akses cepat, aman, dan efisien dengan teknologi terdepan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <a href="{{ route('booking.create') }}" class="btn-modern relative overflow-hidden">
                    <i class="fas fa-calendar-check mr-2"></i> Booking Sekarang
                </a>
            </div>
            <div class="mt-12">
                <p class="text-lg text-gray-500 dark:text-gray-400 italic">
                    "Temukan slot parkir ideal Anda dalam hitungan detik."
                </p>
            </div>
        </div>

        <div class="lottie-car-container float"> {{-- Changed class name here --}}
            <lottie-player
                src="{{ asset('animations/YQm7cmkAZv.json') }}" {{-- Corrected path to public/animations --}}
                background="transparent"
                speed="1"
                style="width: 100%; height: 100%;"
                loop
                autoplay
                class="mx-auto"
            ></lottie-player>
        </div>
    </div>

    <div class="absolute top-1/4 left-10 pulse-ring opacity-20"></div>
    <div class="absolute bottom-1/4 right-10 pulse-ring opacity-20" style="animation-delay: 1s;"></div>
</section>

<section class="py-20 relative">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16 fade-in">
            <h2 class="text-5xl font-bold text-gradient mb-6">Bagaimana Cara Kerjanya?</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">
                Tiga langkah mudah untuk pengalaman parkir yang sempurna
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card-modern text-center fade-in relative group" style="animation-delay: 0.2s;">
                <div class="relative mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-car-side text-3xl text-white"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        1
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-4">Pilih Slot Anda</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Pilih jenis kendaraan dan slot parkir yang tersedia dengan interface yang intuitif dan mudah digunakan.
                </p>
            </div>

            <div class="card-modern text-center fade-in relative group" style="animation-delay: 0.4s;">
                <div class="relative mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-qrcode text-3xl text-white"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        2
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-4">Dapatkan QR Code</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Setelah booking berhasil, QR Code unik akan langsung muncul dan siap untuk digunakan.
                </p>
            </div>

            <div class="card-modern text-center fade-in relative group" style="animation-delay: 0.6s;">
                <div class="relative mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-door-open text-3xl text-white"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-r from-green-400 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                        3
                    </div>
                </div>
                <h3 class="text-2xl font-bold mb-4">Scan & Parkir</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Tunjukkan QR Code di portal, scan dengan mudah, dan masuk ke slot parkir yang telah dipesan.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16 fade-in">
            <h2 class="text-5xl font-bold text-gradient mb-6">Mengapa Memilih SmartPark?</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">
                Teknologi canggih untuk pengalaman parkir terbaik
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="card-modern text-center fade-in" style="animation-delay: 0.1s;">
                <i class="fas fa-bolt text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Super Cepat</h3>
                <p class="text-gray-600 dark:text-gray-400">Booking dalam hitungan detik</p>
            </div>

            <div class="card-modern text-center fade-in" style="animation-delay: 0.2s;">
                <i class="fas fa-shield-alt text-4xl text-green-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Aman Terpercaya</h3>
                <p class="text-gray-600 dark:text-gray-400">Keamanan data terjamin</p>
            </div>

            <div class="card-modern text-center fade-in" style="animation-delay: 0.3s;">
                <i class="fas fa-mobile-alt text-4xl text-blue-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Akses Mudah</h3>
                <p class="text-gray-600 dark:text-gray-400">Aplikasi mobile yang intuitif</p>
            </div>
            <div class="card-modern text-center fade-in" style="animation-delay: 0.4s;">
                <i class="fas fa-chart-line text-4xl text-purple-500 mb-4"></i>
                <h3 class="text-xl font-bold mb-3">Efisiensi Tinggi</h3>
                <p class="text-gray-600 dark:text-gray-400">Optimalkan waktu parkir Anda</p>
            </div>
        </div>
    </div>
</section>
<section class="py-20 bg-gradient-to-r from-blue-500 to-purple-500 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl lg:text-5xl font-bold mb-6">
            Siap untuk Pengalaman Parkir yang Lebih Baik?
        </h2>
        <p class="text-xl mb-8">
            Bergabunglah dengan ribuan pengguna yang telah merasakan kemudahan SmartPark.
        </p>
        <a href="{{ route('booking.create') }}" class="btn-modern relative overflow-hidden">
            <i class="fas fa-calendar-check mr-2"></i> Mulai Sekarang
        </a>
    </div>
</section>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add fade-in effect to elements with class 'fade-in'
        const fadeInElements = document.querySelectorAll('.fade-in');
        fadeInElements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.2}s`;
            el.classList.add('animate__animated', 'animate__fadeIn');
        });
        // Add float effect to the Lottie car container (changed class name)
        const carContainer = document.querySelector('.lottie-car-container');
        carContainer.classList.add('animate__animated', 'animate__float');
    });
</script>
@endsection