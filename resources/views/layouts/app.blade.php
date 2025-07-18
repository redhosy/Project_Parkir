<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPark - {{ $title ?? 'Sistem Parkir Modern' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
        
    <style type="text/tailwindcss">
        @layer base {
            body {
                font-family: 'Inter', sans-serif;
                @apply bg-gray-100 text-gray-800;
            }
            :root {
                --primary: #4361ee;
                --secondary: #3f37c9;
                --success: #4cc9f0;
                --danger: #f72585;
                --warning: #ffc107;
            }
            .navbar-brand {
                font-weight: 700;
            }
            .card-hover:hover {
                transform: translateY(-5px);
                transition: transform 0.3s ease;
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
            .btn-modern {
                @apply bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-100;
            }
            .btn-outline-modern {
                @apply border-2 border-blue-500 text-blue-500 font-bold py-3 px-6 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105 hover:bg-blue-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-100;
            }
            .form-control-modern {
                @apply block w-full px-4 py-2 text-gray-800 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50;
            }
            .modal-content-modern {
                @apply rounded-xl shadow-2xl border border-gray-300;
            }
            .modal-header-modern {
                @apply bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-t-xl;
            }
            .modal-footer-modern {
                @apply bg-gray-50 rounded-b-xl;
            }
            .text-gradient {
                @apply text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div id="notification-container" class="fixed top-4 right-4 z-50 flex flex-col-reverse space-y-2 space-y-reverse"></div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-md">
        <div class="container">
            <a class="navbar-brand text-gradient" href="/">
                <i class="fas fa-parking me-2"></i>SmartPark
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-5 shadow-inner">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} SmartPark - Sistem Manajemen Parkir</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode.js/1.0.0/qrcode.min.js"></script>

    <script>
        function showNotification(message, type = 'info') {
            const container = document.getElementById('notification-container');
            if (!container) return;

            const notificationDiv = document.createElement('div');
            notificationDiv.className = `p-4 rounded-lg shadow-xl flex items-center space-x-3 transition-all duration-500 ease-out transform translate-x-full opacity-0
                ${type === 'success' ? 'bg-green-500' :
                   type === 'error' ? 'bg-red-500' :
                   type === 'warning' ? 'bg-yellow-500' :
                   'bg-blue-500'}
                text-white`;

            let iconClass = '';
            if (type === 'success') iconClass = 'fas fa-check-circle';
            else if (type === 'error') iconClass = 'fas fa-times-circle';
            else if (type === 'warning') iconClass = 'fas fa-exclamation-triangle';
            else iconClass = 'fas fa-info-circle';

            notificationDiv.innerHTML = `
                <i class="${iconClass} text-xl"></i>
                <p class="font-semibold text-lg">${message}</p>
            `;

            container.appendChild(notificationDiv);

            setTimeout(() => {
                notificationDiv.classList.remove('translate-x-full', 'opacity-0');
                notificationDiv.classList.add('translate-x-0', 'opacity-100');
            }, 100);

            setTimeout(() => {
                notificationDiv.classList.remove('translate-x-0', 'opacity-100');
                notificationDiv.classList.add('translate-x-full', 'opacity-0');
                notificationDiv.addEventListener('transitionend', () => notificationDiv.remove());
            }, 5000);
        }

        window.generateQrCode = function(elementId, text) {
            const container = document.getElementById(elementId);
            if (container) {
                container.innerHTML = '';
                new QRCode(container, {
                    text: text,
                    width: 250,
                    height: 250,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>