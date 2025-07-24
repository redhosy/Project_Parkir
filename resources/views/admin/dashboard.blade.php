@extends('layouts.admin')

@section('title', 'Dashboard Admin - SmartPark')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
        </h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Available Slots -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Slot Tersedia</p>
                    <h3 id="stat-available" class="text-4xl font-bold mt-2">{{ $availableSlotsCount }}</h3>
                </div>
                <div class="bg-white/20 rounded-xl p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-blue-100">
                <span class="text-sm">Status saat ini</span>
            </div>
        </div>

        <!-- Booked Slots -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Dibooking</p>
                    <h3 id="stat-booked" class="text-4xl font-bold mt-2">{{ $bookedSlotsCount }}</h3>
                </div>
                <div class="bg-white/20 rounded-xl p-3">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-yellow-100">
                <span class="text-sm">Menunggu kedatangan</span>
            </div>
        </div>

        <!-- Occupied Slots -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-green-100 text-sm font-medium">Terisi</p>
                    <h3 id="stat-occupied" class="text-4xl font-bold mt-2">{{ $occupiedSlotsCount }}</h3>
                </div>
                <div class="bg-white/20 rounded-xl p-3">
                    <i class="fas fa-car text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-green-100">
                <span class="text-sm">Kendaraan terparkir</span>
            </div>
        </div>

        <!-- Total Slots -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Slot</p>
                    <h3 id="stat-total" class="text-4xl font-bold mt-2">{{ $totalSlotsCount }}</h3>
                </div>
                <div class="bg-white/20 rounded-xl p-3">
                    <i class="fas fa-parking text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-purple-100">
                <span class="text-sm">Kapasitas total</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const API_BASE_URL = '{{ url("api") }}';

    function fetchParkingData() {
        fetch(`${API_BASE_URL}/parking-slots`)
            .then(response => response.json())
            .then(slots => {
                document.getElementById('stat-available').textContent = slots.filter(s => s.status === 'available').length;
                document.getElementById('stat-booked').textContent = slots.filter(s => s.status === 'booked').length;
                document.getElementById('stat-occupied').textContent = slots.filter(s => s.status === 'occupied').length;
                document.getElementById('stat-total').textContent = slots.length;
            })
            .catch(error => {
                console.error('Error fetching parking data:', error);
                showNotification('Gagal memuat data parkir.', 'error');
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchParkingData();
    });
</script>
@endpush
@endsection