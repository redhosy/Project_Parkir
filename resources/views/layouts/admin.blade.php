<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - SmartPark')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
    <script>
        // Konfigurasi Tailwind untuk dark mode
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {},
            },
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.css" rel="stylesheet" />
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.0.0/flowbite.min.js"></script>
    <script>
        // Basic setup for Flowbite
        window.Flowbite = {
            Modal: flowbite.Modal
        };
    </script>
    <!-- Global Modal Handler -->
    <script src="{{ asset('js/modal-handler.js') }}"></script>
    @stack('styles')

    <style>
        /* Date picker icon color fix for dark mode */
        .dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);  /* Makes the icon white in dark mode */
        }
        
        /* Sidebar styles */
        .admin-sidebar {
            width: 260px;
            transition: all 0.3s ease;
        }
        
        .admin-sidebar.collapsed {
            width: 80px;
        }

        .admin-sidebar.collapsed span {
            opacity: 0;
            transition: opacity 0.2s;
            white-space: nowrap;
            visibility: hidden;
        }

        .admin-sidebar.collapsed .logo-text {
            display: none;
        }

        .admin-content {
            margin-left: 260px;
            transition: all 0.3s ease;
        }

        .admin-content.expanded {
            margin-left: 80px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #4b5563;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            margin: 0.25rem 0.5rem;
            overflow: hidden;
            white-space: nowrap;
        }

        .dark .sidebar-link {
            color: #e5e7eb;
        }

        .sidebar-link span {
            opacity: 1;
            transition: opacity 0.2s;
        }

        .sidebar-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: #1f2937;
        }

        .dark .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .dark .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.5);
            color: white;
        }

        .sidebar-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 1rem;
        }

        /* Dashboard card stats */
        .stat-card {
            @apply bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transition-all duration-300 hover:transform hover:scale-105;
        }

        .stat-icon {
            @apply w-12 h-12 rounded-full flex items-center justify-center text-2xl;
        }

        /* Theme Toggle */
        .theme-toggle {
            @apply relative inline-flex items-center p-2 rounded-full transition-colors duration-300;
        }
        
        /* Enhanced SweetAlert2 Styles - Simple and Elegant */
        .swal2-modal-lg {
            @apply font-sans bg-white dark:bg-gray-800 rounded-md shadow-md border border-gray-200 dark:border-gray-700;
        }

        .swal2-close-modern {
            @apply h-8 w-8 rounded-md flex items-center justify-center text-gray-400 
                   hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300
                   hover:bg-gray-100 dark:hover:bg-gray-700
                   transition-colors focus:outline-none;
            position: absolute;
            top: 12px;
            right: 12px;
        }

        .swal2-html-container {
            @apply overflow-visible p-0;
        }
        
        /* Form labels with visual indication */
        .form-group label {
            @apply relative inline-block;
        }
        
        .form-group label.required::after {
            content: "*";
            @apply absolute text-red-500 text-sm -top-1 ml-0.5;
        }
        
        /* Simple focus state without animation */
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            @apply border-indigo-500 ring-1 ring-indigo-500 ring-opacity-50 outline-none;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <!-- Sidebar -->
    <aside class="admin-sidebar fixed top-0 left-0 h-full bg-white dark:bg-gray-800 text-gray-800 dark:text-white z-50 border-r border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-parking text-white text-xl"></i>
                </div>
                <span class="text-xl font-bold">SmartPark</span>
            </div>
        </div>

        <nav class="mt-4">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.parking_slots.index') }}" class="sidebar-link {{ request()->routeIs('admin.parking_slots.*') ? 'active' : '' }}">
                <i class="fas fa-parking"></i>
                <span>Kelola Slot Parkir</span>
            </a>

            <a href="{{ route('admin.parking_rates.index') }}" class="sidebar-link {{ request()->routeIs('admin.parking_rates.*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i>
                <span>Kelola Tarif Parkir</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Kelola Pengguna</span>
            </a>

            <a href="{{ route('admin.validasi-qr') }}" class="sidebar-link {{ request()->routeIs('admin.validasi-qr') ? 'active' : '' }}">
                <i class="fas fa-qrcode"></i>
                <span>Validasi QR</span>
            </a>

            <a href="{{ route('admin.riwayat-parkir') }}" class="sidebar-link {{ request()->routeIs('admin.riwayat-parkir') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Riwayat Parkir</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-content min-h-screen bg-gray-100 dark:bg-gray-900 pb-8">
        <!-- Top Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-md">
            <div class="mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button class="theme-toggle p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" onclick="toggleTheme()">
                            <i class="fas fa-sun text-yellow-500 dark:hidden"></i>
                            <i class="fas fa-moon text-blue-300 hidden dark:block"></i>
                        </button>

                        <div class="relative">
                            <button class="flex items-center text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                <span class="mr-2">{{ Auth::user()->name }}</span>
                                <i class="fas fa-user-circle text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="px-8 py-6">
            @yield('content')
        </div>
    </main>

    <script>
        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                document.querySelector('.theme-toggle i').classList.remove('fa-moon');
                document.querySelector('.theme-toggle i').classList.add('fa-sun');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                document.querySelector('.theme-toggle i').classList.remove('fa-sun');
                document.querySelector('.theme-toggle i').classList.add('fa-moon');
            }
        }

        // Sidebar Toggle
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.admin-sidebar');
            const content = document.querySelector('.admin-content');
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
        });

        // Load saved theme and initialize Flowbite globally
        document.addEventListener('DOMContentLoaded', function() {
            // Theme initialization
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                document.querySelector('.theme-toggle i').classList.remove('fa-sun');
                document.querySelector('.theme-toggle i').classList.add('fa-moon');
            }
            
            // Make sure Flowbite is available globally for modals
            if (typeof flowbite !== 'undefined' && !window.Flowbite) {
                window.Flowbite = {
                    Modal: flowbite.Modal
                };
                console.log('Flowbite initialized globally');
            }
        });
        
        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            
            // Set classes based on notification type
            let bgColor, textColor, icon;
            switch(type) {
                case 'success':
                    bgColor = 'bg-green-100';
                    textColor = 'text-green-800';
                    icon = '<i class="fas fa-check-circle text-green-500 mr-2"></i>';
                    break;
                case 'error':
                    bgColor = 'bg-red-100';
                    textColor = 'text-red-800';
                    icon = '<i class="fas fa-exclamation-circle text-red-500 mr-2"></i>';
                    break;
                case 'warning':
                    bgColor = 'bg-yellow-100';
                    textColor = 'text-yellow-800';
                    icon = '<i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>';
                    break;
                default:
                    bgColor = 'bg-blue-100';
                    textColor = 'text-blue-800';
                    icon = '<i class="fas fa-info-circle text-blue-500 mr-2"></i>';
            }
            
            notification.className = `fixed top-5 right-5 z-50 p-4 rounded-lg shadow-lg flex items-center ${bgColor} ${textColor} transform transition-all duration-300 ease-in-out translate-y-0 opacity-100`;
            notification.innerHTML = `${icon} ${message}`;
            
            document.body.appendChild(notification);
            
            // Hide and remove notification after 5 seconds
            setTimeout(() => {
                notification.classList.replace('translate-y-0', '-translate-y-5');
                notification.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 5000);
        }
    </script>

    <!-- Modals Container -->
    @stack('modals')

    <!-- Scripts stacked from pages -->
    @stack('scripts')
</body>
</html>
