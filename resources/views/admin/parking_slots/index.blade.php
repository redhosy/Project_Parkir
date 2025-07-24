@extends('layouts.admin')

@section('title', 'Manajemen Slot Parkir - SmartPark')

@section('content')
<div class="space-y-6">
    <!-- Page Title and Action Button -->
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-6 h-12 flex items-center justify-center mr-2">
                <i class="fas fa-parking text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Slot Parkir</h1>
        </div>
        <button type="button"
                data-modal-target="addParkingSlotModal"
                data-modal-toggle="addParkingSlotModal"
                class="btn-modern group relative overflow-hidden px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-300">
            <span class="relative z-10 flex items-center text-white">
                <i class="fas fa-plus mr-2 transition-transform group-hover:rotate-180 duration-300"></i>
                Tambah Slot
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></div>
        </button>
    </div>

    <!-- Search Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.parking_slots.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Kode Slot Search -->
                <div>
                    <label for="search_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kode Slot
                    </label>
                    <input type="text" 
                           name="search_code" 
                           id="search_code" 
                           value="{{ request('search_code') }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                  bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                                  focus:border-purple-500 dark:focus:border-purple-500 focus:outline-none 
                                  focus:ring-1 focus:ring-purple-500 py-2 px-3 text-sm"
                           placeholder="Cari kode slot...">
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="filter_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Jenis Kendaraan
                    </label>
                    <select name="filter_type" 
                            id="filter_type"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                                   focus:border-purple-500 dark:focus:border-purple-500 focus:outline-none 
                                   focus:ring-1 focus:ring-purple-500 py-2 px-3 text-sm">
                        <option value="">Semua</option>
                        <option value="motor" {{ request('filter_type') == 'motor' ? 'selected' : '' }}>Motor</option>
                        <option value="mobil" {{ request('filter_type') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="truk" {{ request('filter_type') == 'truk' ? 'selected' : '' }}>Truk</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="filter_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status
                    </label>
                    <select name="filter_status" 
                            id="filter_status"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                                   focus:border-purple-500 dark:focus:border-purple-500 focus:outline-none 
                                   focus:ring-1 focus:ring-purple-500 py-2 px-3 text-sm">
                        <option value="">Semua</option>
                        <option value="available" {{ request('filter_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="booked" {{ request('filter_status') == 'booked' ? 'selected' : '' }}>Dibooking</option>
                        <option value="occupied" {{ request('filter_status') == 'occupied' ? 'selected' : '' }}>Terisi</option>
                        <option value="maintenance" {{ request('filter_status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>

                <!-- Search Button -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg 
                                   transition-colors duration-150 flex items-center justify-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Parking Slots Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kode Slot</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tarif/Jam</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Zona</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($slots as $slot)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $slot->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $slot->location_description ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ ucfirst($slot->type) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($slot->parkingRate)
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($slot->parkingRate->rate, 0) }}
                                    @if($slot->parkingRate->is_flat_rate)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">(Flat)</span>
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">(Per Jam)</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($slot->parkingRate->duration_end_hour)
                                        {{ $slot->parkingRate->duration_start_hour }} - {{ $slot->parkingRate->duration_end_hour }} jam
                                    @elseif($slot->parkingRate->is_flat_rate)
                                        ≥ {{ $slot->parkingRate->duration_start_hour }} jam
                                    @else
                                        ≥ {{ $slot->parkingRate->duration_start_hour }} jam
                                    @endif
                                </div>
                            @else
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Tidak diatur</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                Zona {{ $slot->area }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($slot->status) {
                                    'available' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                    'booked' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
                                    'occupied' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                    'maintenance' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                };
                                $statusText = match($slot->status) {
                                    'available' => 'Tersedia',
                                    'booked' => 'Dibooking',
                                    'occupied' => 'Terisi',
                                    'maintenance' => 'Maintenance',
                                    default => 'Tidak Diketahui'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <button type="button"
                                    onclick="window.openEditModal({{ json_encode($slot) }})"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200 p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/20">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                    data-id="{{ $slot->id }}"
                                    class="delete-slot text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200 p-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/20">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-parking text-4xl mb-3"></i>
                                <p class="text-sm">Tidak ada slot parkir yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($slots->hasPages())
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-t border-gray-200 dark:border-gray-700">
            {{ $slots->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal initialization is now handled by global modal-handler.js
    console.log('Parking slots page initialized');

    // Note: openEditModal is now provided globally by modal-handler.js

    // Function to show notification toast
    function showNotification(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out
                          ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white animate-fade-in-down`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);

        // Remove the toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // This function is no longer needed as we're using openEditModal

    // Handle Edit Form Submit
    document.getElementById('editParkingSlotForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        
        try {
            const id = document.getElementById('edit_id').value;
            const response = await fetch(`/admin/parking-slots/${id}`, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                editModal.hide();
                showNotification('Slot parkir berhasil diperbarui');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            showNotification(error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Handle Delete with SweetAlert2
    document.querySelectorAll('.delete-slot').forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.dataset.id;
            
            const result = await Swal.fire({
                title: 'Anda yakin?',
                text: "Slot parkir ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    popup: 'swal-wide-buttons',
                    confirmButton: 'swal-button swal-button-confirm',
                    cancelButton: 'swal-button swal-button-cancel',
                }
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/parking-slots/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        await Swal.fire({
                            title: 'Berhasil!',
                            text: 'Slot parkir berhasil dihapus',
                            icon: 'success',
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700'
                            }
                        });
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat menghapus slot parkir');
                    }
                } catch (error) {
                    await Swal.fire({
                        title: 'Error!',
                        text: error.message,
                        icon: 'error',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700'
                        }
                    });
                }
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out;
    }

    @keyframes fadeInDown {
        0% {
            opacity: 0;
            transform: translateY(-10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Improve action buttons visibility */
    .text-blue-600.p-2:hover, .text-red-600.p-2:hover {
        background-color: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }
    
    .dark .text-blue-400.p-2:hover, .dark .text-red-400.p-2:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    /* Action Buttons */
    .text-blue-600.p-2, .text-red-600.p-2 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .text-blue-600.p-2:hover {
        background-color: rgba(37, 99, 235, 0.1);
        transform: scale(1.1);
    }
    
    .text-red-600.p-2:hover {
        background-color: rgba(220, 38, 38, 0.1);
        transform: scale(1.1);
    }
    
    .dark .text-blue-400.p-2:hover {
        background-color: rgba(96, 165, 250, 0.15);
    }
    
    .dark .text-red-400.p-2:hover {
        background-color: rgba(248, 113, 113, 0.15);
    }
    
    /* SweetAlert2 Custom Styling */
    .swal-wide-buttons {
        width: 28em !important;
        max-width: 90% !important;
    }
    
    .swal-button {
        padding: 8px 16px !important;
        margin: 0 4px !important;
        border-radius: 6px !important;
        font-weight: 500 !important;
        font-size: 0.95em !important;
        transition: all 0.2s ease !important;
    }
    
    .swal-button-confirm {
        background-color: #dc2626 !important; /* red-600 */
        color: white !important;
    }
    
    .swal-button-confirm:hover {
        background-color: #b91c1c !important; /* red-700 */
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    }
    
    .swal-button-cancel {
        background-color: #4b5563 !important; /* gray-600 */
        color: white !important;
    }
    
    .swal-button-cancel:hover {
        background-color: #374151 !important; /* gray-700 */
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endpush

@push('modals')
    @include('admin.parking_slots._add_modal')
    @include('admin.parking_slots._edit_modal')
@endpush
@endsection