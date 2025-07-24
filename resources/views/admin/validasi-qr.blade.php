@extends('layouts.admin')

@section('title', 'Validasi QR - SmartPark')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-qrcode mr-2"></i>Validasi QR Code
        </h1>
        <div class="flex space-x-2">
            <button class="btn-modern bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 shadow-md" onclick="fetchParkingData()">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- QR Scanner Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" id="qrCodeInput" 
                   class="flex-grow px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white" 
                   placeholder="Scan atau Masukkan Kode Booking" autofocus>
            <button id="scanEntryBtn" class="btn-modern bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 shadow-md">
                <i class="fas fa-arrow-alt-circle-right mr-2"></i>Masuk
            </button>
            <button id="scanExitBtn" class="btn-outline-modern border-2 border-red-500 bg-white hover:bg-red-50 text-red-500 font-medium py-2 px-4 rounded-lg transition duration-300 shadow-md">
                <i class="fas fa-arrow-alt-circle-left mr-2"></i>Pulang
            </button>
        </div>
        <div id="scanResult" class="mt-4 p-4 rounded-lg text-center font-medium hidden"></div>
    </div>

    <!-- Parking Map -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">
            <i class="fas fa-map-marked-alt mr-2"></i>Peta Slot Parkir
        </h2>
        <div id="parking-slots-map" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        </div>
    </div>
</div>

@push('scripts')
<script>
    const API_BASE_URL = '{{ url("api") }}';

    function renderParkingSlotsMap(slots) {
        const mapContainer = document.getElementById('parking-slots-map');
        mapContainer.innerHTML = '';

        slots.forEach(slot => {
            let statusClass = '';
            let icon = '';
            if (slot.status === 'available') {
                statusClass = 'bg-green-100 border-green-400 text-green-800';
                icon = '<i class="fas fa-check-circle text-green-500"></i>';
            } else if (slot.status === 'booked') {
                statusClass = 'bg-yellow-100 border-yellow-400 text-yellow-800';
                icon = '<i class="fas fa-calendar-check text-yellow-500"></i>';
            } else if (slot.status === 'occupied') {
                statusClass = 'bg-red-100 border-red-400 text-red-800';
                icon = '<i class="fas fa-car text-red-500"></i>';
            } else if (slot.status === 'maintenance') {
                statusClass = 'bg-gray-100 border-gray-400 text-gray-800';
                icon = '<i class="fas fa-tools text-gray-500"></i>';
            }

            const slotElement = document.createElement('div');
            slotElement.className = `p-4 rounded-lg shadow-md text-center border-2 ${statusClass} transition duration-300 transform hover:scale-105`;
            slotElement.innerHTML = `
                <div class="text-3xl mb-2">${icon}</div>
                <div class="font-bold text-xl">${slot.code}</div>
                <div class="text-sm">${slot.type.charAt(0).toUpperCase() + slot.type.slice(1)}</div>
                <div class="text-xs mt-1">Status: ${getStatusText(slot.status)}</div>
            `;
            mapContainer.appendChild(slotElement);
        });
    }

    function getStatusText(status) {
        switch (status) {
            case 'available': return 'Tersedia';
            case 'booked': return 'Dibooking';
            case 'occupied': return 'Terisi';
            case 'maintenance': return 'Maintenance';
            default: return 'Tidak Diketahui';
        }
    }

    function fetchParkingData() {
        fetch(`${API_BASE_URL}/parking-slots`)
            .then(response => response.json())
            .then(slots => {
                renderParkingSlotsMap(slots);
            })
            .catch(error => {
                console.error('Error fetching parking data:', error);
                showNotification('Gagal memuat data parkir.', 'error');
            });
    }

    async function handleScan(type) {
        const qrCode = document.getElementById('qrCodeInput').value.trim();
        const scanResultDiv = document.getElementById('scanResult');

        if (!qrCode) {
            showNotification('Mohon masukkan Kode Booking.', 'warning');
            return;
        }

        scanResultDiv.classList.add('hidden');

        const endpoint = type === 'entry' ? 'admin/scan-entry' : 'admin/scan-exit';
        try {
            const response = await fetch(`${API_BASE_URL}/${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ qr_code: qrCode })
            });

            const result = await response.json();

            if (response.ok) {
                showNotification(result.message, 'success');
                scanResultDiv.className = 'mt-4 p-3 rounded-lg text-center font-semibold bg-green-100 text-green-800';
                scanResultDiv.textContent = result.message;
                fetchParkingData();
            } else {
                let errorMessage = result.message || 'Operasi gagal.';
                if (result.errors) {
                    errorMessage += '\n' + Object.values(result.errors).flat().join('\n');
                }
                showNotification(errorMessage, 'error');
                scanResultDiv.className = 'mt-4 p-3 rounded-lg text-center font-semibold bg-red-100 text-red-800';
                scanResultDiv.textContent = errorMessage;
            }
            scanResultDiv.classList.remove('hidden');
            document.getElementById('qrCodeInput').value = '';
        } catch (error) {
            console.error('Error during QR scan:', error);
            showNotification('Terjadi kesalahan jaringan atau server saat memindai QR.', 'error');
            scanResultDiv.className = 'mt-4 p-3 rounded-lg text-center font-semibold bg-red-100 text-red-800';
            scanResultDiv.textContent = 'Terjadi kesalahan jaringan atau server.';
            scanResultDiv.classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchParkingData();

        document.getElementById('scanEntryBtn').addEventListener('click', () => handleScan('entry'));
        document.getElementById('scanExitBtn').addEventListener('click', () => handleScan('exit'));
    });
</script>
@endpush
@endsection