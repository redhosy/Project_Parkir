<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SmartPark')</title>

    {{-- Load Tailwind CSS (prefer using a build process if possible, or keep CDN for simplicity) --}}
    {{-- If you're using Laravel Mix/Vite to compile Tailwind, you'd use asset('css/app.css') --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    {{-- External CSS Libraries --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.3.1/css/hover-min.css">

    {{-- Lottie Player JS --}}
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    {{-- Particles.js (if used for background animation, ensure it's loaded before its init script) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    
    {{-- Alpine.js for dropdowns --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


    <style>
        /* Custom CSS Variables for Theme */
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #8b5cf6;
            --accent-color: #06d6a0;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --bg-primary: #111827;
            --bg-secondary: #1f2937;
            --shadow: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        /* Modern Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 3s ease-in-out infinite alternate;
        }

        @keyframes gradient-shift {
            0% { filter: hue-rotate(0deg); }
            100% { filter: hue-rotate(15deg); }
        }

        /* Modern Buttons */
        .btn-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-modern:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover:before {
            left: 100%;
        }

        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        }

        .btn-outline-modern {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-outline-modern:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        /* Floating Animation */
        .float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Fade In Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card Hover Effects */
        .card-modern {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-modern:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px var(--shadow);
        }

        /* Navbar Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        [data-theme="dark"] .navbar {
            background: rgba(17, 24, 39, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Theme Toggle Button */
        .theme-toggle {
            width: 50px;
            height: 26px;
            background: var(--bg-secondary);
            border-radius: 50px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid var(--primary-color);
        }

        .theme-toggle-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .sun-icon {
            left: 6px;
            color: var(--primary-color);
        }

        .moon-icon {
            right: 6px;
            color: var(--text-secondary);
        }

        [data-theme="dark"] .sun-icon {
            color: var(--text-secondary);
        }

        [data-theme="dark"] .moon-icon {
            color: var(--primary-color);
        }

        /* Lottie Car Container (renamed from car-3d-container for clarity) */
        .lottie-car-container {
            aspect-ratio: 1.23 / 1; 
            width: 100%; 
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px var(--shadow);
        }

        .lottie-car-container lottie-player { 
            width: 100%;
            height: 100%;
        }

        /* Pulse Animation */
        .pulse-ring {
            content: '';
            width: 100px;
            height: 100px;
            border: 2px solid var(--primary-color);
            border-radius: 50%;
            position: absolute;
            animation: pulse-ring 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        /* Particle Background */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 50%;
            opacity: 0.3;
            animation: float-particle 20s linear infinite;
        }

        @keyframes float-particle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-10vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Ripple Effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="particles" id="particles"></div>

    <nav class="navbar fixed top-0 left-0 right-0 z-50 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-parking text-white text-xl"></i>
                </div>
                <a href="{{ route('welcome') }}" class="text-2xl font-bold text-gradient no-underline">SmartPark</a>
            </div>

            <div class="flex items-center space-x-6">
                <div class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-sun theme-toggle-icon sun-icon"></i>
                    <i class="fas fa-moon theme-toggle-icon moon-icon"></i>
                </div>

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <img src="{{ Auth::user()->avatar_url }}" 
                                 alt="Profile" 
                                 class="w-8 h-8 rounded-full border-2 border-blue-500 object-cover">
                            <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-user mr-2"></i>Profile
                            </a>
                            <a href="{{ route('booking.history') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-history mr-2"></i>Riwayat Booking
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-700 dark:text-gray-300">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="md:hidden mt-4 pb-4 border-t border-gray-200 dark:border-gray-700 hidden">
            <div class="flex flex-col space-y-3 pt-4">
                <a href="{{ route('welcome') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition-colors">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="{{ route('booking.create') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition-colors">
                    <i class="fas fa-calendar-plus mr-2"></i>Booking
                </a>
                <a href="{{ route('booking.history') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition-colors">
                    <i class="fas fa-history mr-2"></i>Riwayat
                </a>
                @auth
                    <a href="{{ route('profile') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 transition-colors">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-600 hover:text-red-700 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-20">
        @yield('content')
    </main>

    <footer class="py-12 bg-gray-900 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-parking text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-gradient">SmartPark</span>
                    </div>
                    <p class="text-gray-400 mb-4">Parkir pintar untuk kehidupan yang lebih mudah. Teknologi terdepan untuk pengalaman parkir terbaik.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 pt-6 mt-8 text-center">
                <p class="text-gray-400">© {{ date('Y') }} SmartPark. Semua hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Theme Toggle Functionality
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        });

        // Particle System
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;

            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Initialize everything when page loads
        window.addEventListener('load', function() {
            createParticles();

            // Observe all fade-in elements
            document.querySelectorAll('.fade-in').forEach(el => {
                el.style.animationPlayState = 'paused';
                observer.observe(el);
            });

            // Add some random delays to stagger animations
            document.querySelectorAll('.card-modern').forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
            });
        });

        // Add dynamic hover effects
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-modern, .btn-outline-modern').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px) scale(1.02)';
                });

                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });

                // Add click ripple effect
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuButton = event.target.closest('[onclick="toggleMobileMenu()"]');

            if (!menuButton && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>