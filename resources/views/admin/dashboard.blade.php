@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0 text-gradient"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</h2>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle btn-modern" type="button" id="dashboardActions" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-1"></i>Aksi Cepat
                    </button>
                    <ul class="dropdown-menu shadow-lg rounded-lg" aria-labelledby="dashboardActions">
                        <li><a class="dropdown-item hover:bg-gray-100 transition duration-200" href="{{ route('admin.parking_slots.index') }}"><i class="fas fa-plus me-2"></i>Kelola Slot</a></li>
                        <li><a class="dropdown-item hover:bg-gray-100 transition duration-200" href="#"><i class="fas fa-users me-2"></i>Kelola User</a></li>
                        <li><a class="dropdown-item hover:bg-gray-100 transition duration-200" href="#"><i class="fas fa-file-alt me-2"></i>Laporan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-6">
        <div class="col-md-3 mb-4">
            <div class="card bg-blue-600 text-white rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0 text-gray-200">Slot Tersedia</h6>
                            <h2 id="stat-available" class="mb-0 text-3xl font-bold">{{ $availableSlotsCount }}</h2>
                        </div>
                        <div class="icon-shape bg-white text-blue-600 rounded-full p-3 shadow-md">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-yellow-500 text-white rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0 text-yellow-100">Dibooking</h6>
                            <h2 id="stat-booked" class="mb-0 text-3xl font-bold">{{ $bookedSlotsCount }}</h2>
                        </div>
                        <div class="icon-shape bg-white text-yellow-500 rounded-full p-3 shadow-md">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-red-600 text-white rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0 text-red-100">Terisi</h6>
                            <h2 id="stat-occupied" class="mb-0 text-3xl font-bold">{{ $occupiedSlotsCount }}</h2>
                        </div>
                        <div class="icon-shape bg-white text-red-600 rounded-full p-3 shadow-md">
                            <i class="fas fa-car fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-green-600 text-white rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-0 text-green-100">Total Slot</h6>
                            <h2 id="stat-total" class="mb-0 text-3xl font-bold">{{ $totalSlotsCount }}</h2>
                        </div>
                        <div class="icon-shape bg-white text-green-600 rounded-full p-3 shadow-md">
                            <i class="fas fa-parking fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-6">
        <div class="col-12">
            <div class="card rounded-xl shadow-xl p-6 bg-white">
                <h5 class="mb-4 text-2xl font-bold text-gray-800 border-b pb-2"><i class="fas fa-qrcode me-2"></i> Validasi QR Code</h5>
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-4">
                    <input type="text" id="qrCodeInput" class="form-control-modern flex-grow" placeholder="Scan atau Masukkan Kode Booking" autofocus>
                    <button id="scanEntryBtn" class="btn-modern flex-1 sm:flex-none w-full sm:w-auto">
                        <i class="fas fa-arrow-alt-circle-right me-2"></i> Masuk
                    </button>
                    <button id="scanExitBtn" class="btn-outline-modern flex-1 sm:flex-none w-full sm:w-auto">
                        <i class="fas fa-arrow-alt-circle-left me-2"></i> Pulang
                    </button>
                </div>
                <div id="scanResult" class="mt-4 p-3 rounded-lg text-center font-semibold hidden"></div>

                <h5 class="mb-4 text-2xl font-bold text-gray-800 border-b pb-2 mt-8"><i class="fas fa-map-marked-alt me-2"></i> Peta Slot Parkir</h5>
                <div id="parking-slots-map" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card rounded-xl shadow-xl">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-b p-4">
                    <h5 class="mb-0 text-2xl font-bold text-gray-800"><i class="fas fa-history me-2"></i>Booking Terakhir</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">ID Booking</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">User</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Kendaraan</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Slot</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Waktu</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Status</th>
                                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $booking)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-200">
                                    <td class="py-3 px-4">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-3 px-4">{{ $booking->nama_pemesan }}</td>
                                    <td class="py-3 px-4">{{ $booking->merk_kendaraan }} ({{ $booking->license_plate }})</td>
                                    <td class="py-3 px-4">{{ $booking->parkingSlot->code }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }}</td>
                                    <td class="py-3 px-4">
                                        @include('components.status-badge', ['status' => $booking->status])
                                    </td>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">Tidak ada booking terbaru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
                document.getElementById('stat-available').textContent = slots.filter(s => s.status === 'available').length;
                document.getElementById('stat-booked').textContent = slots.filter(s => s.status === 'booked').length;
                document.getElementById('stat-occupied').textContent = slots.filter(s => s.status === 'occupied').length;
                document.getElementById('stat-total').textContent = slots.length;

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