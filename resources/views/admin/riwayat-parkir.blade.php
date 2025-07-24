@extends('layouts.admin')

@section('title', 'Riwayat Parkir - SmartPark')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            <i class="fas fa-history mr-2"></i>Riwayat Parkir
        </h1>
        <div class="flex space-x-2">
            <button class="btn-modern bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 shadow-md" onclick="refreshBookings()">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari</label>
                <div class="relative group">
                    <input type="text" id="search" class="w-full h-11 pl-4 pr-10 rounded-lg border-2 border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-700 dark:text-white
                           group-hover:border-blue-400 dark:group-hover:border-blue-500
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800
                           transition-all duration-200" placeholder="ID, Pengguna, Plat Nomor...">
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-400 group-hover:text-blue-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
            <div>
                <label for="status-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <div class="relative group">
                    <select id="status-filter" class="form-select w-full h-11 pl-4 rounded-lg border-2 border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-700 dark:text-white
                           group-hover:border-blue-400 dark:group-hover:border-blue-500
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800
                           transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="date-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                <div class="relative group">
                    <input type="date" id="date-filter" class="w-full h-11 pl-4 rounded-lg border-2 border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-700 dark:text-white
                           group-hover:border-blue-400 dark:group-hover:border-blue-500
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800
                           transition-all duration-200
                           [color-scheme:dark]">
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    <i class="fas fa-history mr-2"></i>Booking Terbaru
                </h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slot</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $booking->nama_pemesan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $booking->merk_kendaraan }} ({{ $booking->license_plate }})
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $booking->parkingSlot->code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $booking->entry_time ? \Carbon\Carbon::parse($booking->entry_time)->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $booking->exit_time ? \Carbon\Carbon::parse($booking->exit_time)->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @include('components.status-badge', ['status' => $booking->status])
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('booking.show', $booking->id) }}" 
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada booking terbaru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    function refreshBookings() {
        window.location.reload();
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        // Setup search and filter functionality
        const searchInput = document.getElementById('search');
        const statusFilter = document.getElementById('status-filter');
        const dateFilter = document.getElementById('date-filter');
        
        function applyFilters() {
            const searchValue = searchInput.value.trim();
            const statusValue = statusFilter.value;
            const dateValue = dateFilter.value;
            
            let url = new URL(window.location.href);
            
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            
            if (statusValue) {
                url.searchParams.set('status', statusValue);
            } else {
                url.searchParams.delete('status');
            }
            
            if (dateValue) {
                url.searchParams.set('date', dateValue);
            } else {
                url.searchParams.delete('date');
            }
            
            window.location.href = url.toString();
        }
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        
        statusFilter.addEventListener('change', applyFilters);
        dateFilter.addEventListener('change', applyFilters);
        
        // Set filter values from URL params
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }
        if (urlParams.has('status')) {
            statusFilter.value = urlParams.get('status');
        }
        if (urlParams.has('date')) {
            dateFilter.value = urlParams.get('date');
        }
    });
</script>
@endpush
@endsection
