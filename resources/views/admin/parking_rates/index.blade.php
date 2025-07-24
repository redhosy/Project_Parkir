@extends('layouts.admin')

@section('title', 'Manajemen Tarif Parkir - SmartPark')

@section('content')
<div class="space-y-6">
    <!-- Page Title and Action Button -->
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-6 h-12 flex items-center justify-center mr-2">
                <i class="fas fa-tags text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Tarif Parkir</h1>
        </div>
        <button type="button"
                data-modal-target="addRateModal"
                data-modal-toggle="addRateModal"
                class="btn-modern group relative overflow-hidden px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-300">
            <span class="relative z-10 flex items-center text-white">
                <i class="fas fa-plus mr-2 transition-transform group-hover:rotate-180 duration-300"></i>
                Tambah Tarif
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></div>
        </button>
    </div>

    <!-- Search Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.parking_rates.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Vehicle Type Filter -->
                <div>
                    <label for="filter_vehicle_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Jenis Kendaraan
                    </label>
                    <select name="filter_vehicle_type" 
                            id="filter_vehicle_type"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                                   focus:border-purple-500 dark:focus:border-purple-500 focus:outline-none 
                                   focus:ring-1 focus:ring-purple-500 py-2 px-3 text-sm">
                        <option value="">Semua</option>
                        <option value="motor" {{ request('filter_vehicle_type') == 'motor' ? 'selected' : '' }}>Motor</option>
                        <option value="mobil" {{ request('filter_vehicle_type') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="truk" {{ request('filter_vehicle_type') == 'truk' ? 'selected' : '' }}>Truk</option>
                    </select>
                </div>

                <!-- Rate Type Filter -->
                <div>
                    <label for="filter_rate_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipe Tarif
                    </label>
                    <select name="filter_rate_type" 
                            id="filter_rate_type"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                                   bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                                   focus:border-purple-500 dark:focus:border-purple-500 focus:outline-none 
                                   focus:ring-1 focus:ring-purple-500 py-2 px-3 text-sm">
                        <option value="">Semua</option>
                        <option value="hourly" {{ request('filter_rate_type') == 'hourly' ? 'selected' : '' }}>Per Jam</option>
                        <option value="flat" {{ request('filter_rate_type') == 'flat' ? 'selected' : '' }}>Tarif Flat</option>
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

    <!-- Main Content -->
    <div>
        <!-- Tariff Management -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Daftar Tarif Parkir</h2>
                </div>

                <!-- Tariff Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jenis Kendaraan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Durasi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tarif
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tipe Tarif
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($rates as $rate)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $vehicleTypeClass = match($rate->vehicle_type) {
                                                'motor' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                                'mobil' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                                'truk' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                            };
                                            $vehicleTypeText = match($rate->vehicle_type) {
                                                'motor' => 'Motor',
                                                'mobil' => 'Mobil',
                                                'truk' => 'Truk',
                                                default => 'Tidak Diketahui'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vehicleTypeClass }}">
                                            {{ $vehicleTypeText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($rate->duration_end_hour)
                                            {{ $rate->duration_start_hour }} - {{ $rate->duration_end_hour }} jam
                                        @elseif($rate->is_flat_rate)
                                            ≥ {{ $rate->duration_start_hour }} jam (Tarif flat)
                                        @else
                                            ≥ {{ $rate->duration_start_hour }} jam
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($rate->rate, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($rate->is_flat_rate)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                                Tarif Flat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                                                Per Jam
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <button type="button" 
                                                    class="edit-rate-btn text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200 p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/20"
                                                    data-id="{{ $rate->id }}"
                                                    data-vehicle-type="{{ $rate->vehicle_type }}"
                                                    data-duration-start="{{ $rate->duration_start_hour }}"
                                                    data-duration-end="{{ $rate->duration_end_hour }}"
                                                    data-rate="{{ $rate->rate }}"
                                                    data-is-flat="{{ $rate->is_flat_rate ? '1' : '0' }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button"
                                                    class="delete-rate text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200 p-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/20"
                                                    data-id="{{ $rate->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-tags text-4xl mb-3"></i>
                                            <p class="text-sm">Belum ada data tarif parkir.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    {{ $rates->withQueryString()->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Tariff Modal -->
<div id="addRateModal" tabindex="-1" aria-hidden="true" 
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-2xl shadow-xl dark:bg-gray-800 transform transition-all">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t-2xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Tarif Parkir
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="addRateModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <form id="addRateForm" action="{{ route('admin.parking_rates.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Vehicle Type -->
                    <div>
                        <label for="vehicle_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kendaraan <span class="text-red-500">*</span></label>
                        <select id="vehicle_type" name="vehicle_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>

                    <!-- Rate Type -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe Tarif <span class="text-red-500">*</span></label>
                        <div class="flex space-x-4">
                            <div class="flex items-center">
                                <input type="radio" id="rate_type_hourly" name="is_flat_rate" value="0" class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" checked>
                                <label for="rate_type_hourly" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Per Jam</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="rate_type_flat" name="is_flat_rate" value="1" class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="rate_type_flat" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tarif Flat</label>
                            </div>
                        </div>
                    </div>

                    <!-- Duration Start Hour -->
                    <div>
                        <label for="duration_start_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="number" id="duration_start_hour" name="duration_start_hour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required min="0" value="0">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jam mulai berlakunya tarif ini</p>
                    </div>

                    <!-- Duration End Hour -->
                    <div id="duration_end_container">
                        <label for="duration_end_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Akhir</label>
                        <input type="number" id="duration_end_hour" name="duration_end_hour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" min="0">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jam akhir berlakunya tarif ini (kosongkan jika berlaku seterusnya)</p>
                    </div>

                    <!-- Rate -->
                    <div>
                        <label for="rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tarif (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="number" id="rate" name="rate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required min="0" step="500" value="0">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tarif per jam atau tarif flat</p>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-700">
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600" data-modal-hide="addRateModal">
                    Batal
                </button>
                <button type="submit" form="addRateForm" class="text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 transition-all duration-200 ease-in-out transform hover:scale-105">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Tariff Modal -->
<div id="editRateModal" tabindex="-1" aria-hidden="true" 
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-2xl shadow-xl dark:bg-gray-800 transform transition-all">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t-2xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Edit Tarif Parkir <span id="edit-rate-title" class="text-gray-500 dark:text-gray-400"></span>
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="editRateModal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <form id="editRateForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">

                    <!-- Vehicle Type -->
                    <div>
                        <label for="edit_vehicle_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kendaraan <span class="text-red-500">*</span></label>
                        <select id="edit_vehicle_type" name="vehicle_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>

                    <!-- Rate Type -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe Tarif <span class="text-red-500">*</span></label>
                        <div class="flex space-x-4">
                            <div class="flex items-center">
                                <input type="radio" id="edit_rate_type_hourly" name="is_flat_rate" value="0" class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="edit_rate_type_hourly" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Per Jam</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="edit_rate_type_flat" name="is_flat_rate" value="1" class="w-4 h-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="edit_rate_type_flat" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tarif Flat</label>
                            </div>
                        </div>
                    </div>

                    <!-- Duration Start Hour -->
                    <div>
                        <label for="edit_duration_start_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_duration_start_hour" name="duration_start_hour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required min="0">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jam mulai berlakunya tarif ini</p>
                    </div>

                    <!-- Duration End Hour -->
                    <div id="edit_duration_end_container">
                        <label for="edit_duration_end_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Akhir</label>
                        <input type="number" id="edit_duration_end_hour" name="duration_end_hour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" min="0">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jam akhir berlakunya tarif ini (kosongkan jika berlaku seterusnya)</p>
                    </div>

                    <!-- Rate -->
                    <div>
                        <label for="edit_rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tarif (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">Rp</span>
                            <input type="number" id="edit_rate" name="rate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required min="0" step="500">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tarif per jam atau tarif flat</p>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-700">
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600" data-modal-hide="editRateModal">
                    Batal
                </button>
                <button type="submit" form="editRateForm" class="text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-blue-800 transition-all duration-200 ease-in-out transform hover:scale-105">
                    Simpan Perubahan
                </button>
            </div>
            
            <div class="hidden error-messages p-4 mt-2 text-sm text-red-600 bg-red-100 rounded-lg"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure Flowbite is properly defined in the global scope
    if (typeof Flowbite === 'undefined') {
        console.warn('Flowbite not found in global scope, attempting to make it available');
        window.Flowbite = {
            Modal: flowbite.Modal
        };
    }
    
    // Add event listeners to edit buttons
    document.querySelectorAll('.edit-rate-btn').forEach(button => {
        button.addEventListener('click', function() {
            const rateId = this.getAttribute('data-id');
            const vehicleType = this.getAttribute('data-vehicle-type');
            const durationStart = this.getAttribute('data-duration-start');
            const durationEnd = this.getAttribute('data-duration-end');
            const rate = this.getAttribute('data-rate');
            const isFlat = this.getAttribute('data-is-flat') === '1';
            
            // Create rate object with collected data
            const rateData = {
                id: rateId,
                vehicle_type: vehicleType,
                duration_start_hour: durationStart,
                duration_end_hour: durationEnd || null,
                rate: rate,
                is_flat_rate: isFlat
            };
            
            console.log('Edit button clicked, opening modal with data:', rateData);
            
            // Use the openEditModal function defined in _edit_modal.blade.php
            if (typeof window.openEditModal === 'function') {
                window.openEditModal(rateData);
            } else {
                // Fallback: Manually set form values and show modal
                const form = document.getElementById('editRateForm');
                form.action = `/admin/parking-rates/${rateId}`;
                
                // Set values to form fields
                document.getElementById('edit_id').value = rateId;
                document.getElementById('edit_vehicle_type').value = vehicleType;
                document.getElementById('edit_duration_start_hour').value = durationStart;
                document.getElementById('edit_duration_end_hour').value = durationEnd || '';
                document.getElementById('edit_rate').value = rate;
                
                // Set the correct radio button
                if (isFlat) {
                    document.getElementById('edit_rate_type_flat').checked = true;
                } else {
                    document.getElementById('edit_rate_type_hourly').checked = true;
                }
                
                // Show the modal using Flowbite
                const editModalEl = document.getElementById('editRateModal');
                if (typeof flowbite !== 'undefined') {
                    const modal = new flowbite.Modal(editModalEl);
                    modal.show();
                }
            }
        });
    });
    
    // Clean up any existing modal backdrops (prevents issues with multiple backdrops)
    function cleanupModalBackdrops() {
        document.querySelectorAll('[modal-backdrop]').forEach(backdrop => {
            backdrop.remove();
        });
    }
    
    // Initialize modals with Flowbite
    let addModal, editModal;
    
    try {
        const addModalEl = document.getElementById('addRateModal');
        if (addModalEl) {
            addModal = new Flowbite.Modal(addModalEl, {
                backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                onShow: () => {
                    // Ensure body overflow is hidden to prevent scrolling behind modal
                    document.body.style.overflow = 'hidden';
                    cleanupModalBackdrops();
                },
                onHide: () => {
                    // Remove modal backdrop manually when modal is closed
                    cleanupModalBackdrops();
                    // Reset form
                    document.getElementById('addRateForm').reset();
                    // Restore body scrolling
                    document.body.style.overflow = '';
                }
            });
            window.addRateModal = addModal;
        } else {
            console.error('Add Rate Modal element not found');
        }

        const editModalEl = document.getElementById('editRateModal');
        if (editModalEl) {
            editModal = new Flowbite.Modal(editModalEl, {
                backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                onShow: () => {
                    // Ensure body overflow is hidden to prevent scrolling behind modal
                    document.body.style.overflow = 'hidden';
                    cleanupModalBackdrops();
                    console.log('Edit modal is now visible');
                },
                onHide: () => {
                    // Remove modal backdrop manually when modal is closed
                    cleanupModalBackdrops();
                    // Restore body scrolling
                    document.body.style.overflow = '';
                }
            });
            
            // Make the modal instance globally accessible
            window.editRateModal = editModal;
            console.log('Edit modal initialized and stored in window.editRateModal');
        } else {
            console.error('Edit Rate Modal element not found');
        }
    } catch (error) {
        console.error('Error initializing modals:', error);
    }

    // Add click handlers for modal triggers
    document.querySelectorAll('[data-modal-target="addRateModal"]').forEach(el => {
        el.addEventListener('click', () => addModal.show());
    });

    // Function to toggle end hour field based on rate type selection
    function toggleEndHourField(prefix = '') {
        const isFlatRate = document.querySelector(`input[name="is_flat_rate"]:checked`).value === '1';
        const endHourContainer = document.getElementById((prefix ? prefix + '_' : '') + 'duration_end_container');
        
        if (isFlatRate) {
            endHourContainer.classList.add('hidden');
            document.getElementById((prefix ? prefix + '_' : '') + 'duration_end_hour').value = '';
        } else {
            endHourContainer.classList.remove('hidden');
        }
    }

    // Add event listeners for rate type radios
    document.getElementById('rate_type_hourly').addEventListener('change', () => toggleEndHourField());
    document.getElementById('rate_type_flat').addEventListener('change', () => toggleEndHourField());
    document.getElementById('edit_rate_type_hourly').addEventListener('change', () => toggleEndHourField('edit'));
    document.getElementById('edit_rate_type_flat').addEventListener('change', () => toggleEndHourField('edit'));

    // Call initially to set correct state
    toggleEndHourField();

    // Function to handle form validation before submit
    function validateRateForm(form) {
        const startHour = parseInt(form.querySelector('[name="duration_start_hour"]').value);
        const endHourInput = form.querySelector('[name="duration_end_hour"]');
        const endHour = endHourInput.value ? parseInt(endHourInput.value) : null;
        const isFlatRate = form.querySelector('input[name="is_flat_rate"]:checked').value === '1';
        const rate = parseFloat(form.querySelector('[name="rate"]').value);
        
        // Validate start hour
        if (startHour < 0) {
            showNotification('Jam mulai harus bernilai positif', 'error');
            form.querySelector('[name="duration_start_hour"]').focus();
            return false;
        }
        
        // Validate end hour if not flat rate
        if (!isFlatRate && endHour !== null) {
            if (endHour <= startHour) {
                showNotification('Jam akhir harus lebih besar dari jam mulai', 'error');
                endHourInput.focus();
                return false;
            }
        }
        
        // Validate rate amount
        if (rate <= 0) {
            showNotification('Tarif harus lebih dari 0', 'error');
            form.querySelector('[name="rate"]').focus();
            return false;
        }

        return true;
    }

    // Form submission for add rate
    document.getElementById('addRateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validateRateForm(this)) return;

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                addModal.hide();
                showNotification('Tarif parkir berhasil ditambahkan');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat menyimpan tarif');
            }
        } catch (error) {
            showNotification(error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Populate Edit Modal with rate data
    window.populateEditModal = function(rateId) {
        // Find the button with data-rate attribute
        const button = document.querySelector(`button[data-rate][onclick*="populateEditModal(${rateId})"]`);
        if (!button) {
            console.error('Button not found for rate ID:', rateId);
            return;
        }
        
        try {
            const data = JSON.parse(button.getAttribute('data-rate'));
            if (!data) {
                console.error('No data found in data-rate attribute for rate ID:', rateId);
                return;
            }
            
            console.log('Rate data loaded successfully:', data);
            
            // Set form action with correct rate ID
            const form = document.getElementById('editRateForm');
            form.action = `/admin/parking-rates/${data.id}`;
            
            // Set values to form fields
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_vehicle_type').value = data.vehicle_type;
            document.getElementById('edit_duration_start_hour').value = data.duration_start_hour;
            document.getElementById('edit_duration_end_hour').value = data.duration_end_hour || '';
            document.getElementById('edit_rate').value = data.rate;
            
            // Set the correct radio button
            if (data.is_flat_rate) {
                document.getElementById('edit_rate_type_flat').checked = true;
            } else {
                document.getElementById('edit_rate_type_hourly').checked = true;
            }
            
            // Update title with vehicle type
            const vehicleTypeText = {
                'motor': 'Motor',
                'mobil': 'Mobil',
                'truk': 'Truk'
            }[data.vehicle_type] || 'Unknown';
            document.getElementById('edit-rate-title').textContent = `(${vehicleTypeText})`;
            
            // Toggle end hour field visibility
            toggleEndHourField('edit');
            
            // Make sure any previous modal backdrops are removed
            document.querySelectorAll('[modal-backdrop]').forEach(backdrop => {
                backdrop.remove();
            });

            // Show the modal using the global variable
            if (window.editRateModal) {
                window.editRateModal.show();
            } else {
                // If the global variable isn't set, initialize the modal
                const editModalEl = document.getElementById('editRateModal');
                try {
                    // Try multiple approaches to access the Modal class from Flowbite
                    let ModalClass = null;
                    
                    // First try to access from window.Flowbite
                    if (typeof window.Flowbite !== 'undefined' && window.Flowbite.Modal) {
                        ModalClass = window.Flowbite.Modal;
                        console.log('Using window.Flowbite.Modal');
                    } 
                    // Then try to access from global flowbite object
                    else if (typeof flowbite !== 'undefined' && flowbite.Modal) {
                        ModalClass = flowbite.Modal;
                        console.log('Using flowbite.Modal');
                    }
                    
                    if (ModalClass && editModalEl) {
                        window.editRateModal = new ModalClass(editModalEl, {
                            backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40'
                        });
                        window.editRateModal.show();
                    } else {
                        throw new Error('Modal class or element not found');
                    }
                } catch (error) {
                    console.error('Error initializing edit modal:', error);
                    alert('Terjadi kesalahan saat membuka modal edit. Silakan coba lagi.');
                }
            }
        } catch (error) {
            console.error('Error populating edit modal:', error);
            alert('Terjadi kesalahan saat membuka modal edit. Silakan coba lagi.');
        }
    };

    // Handle Edit Form Submit
    document.getElementById('editRateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validateRateForm(this)) return;

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        
        try {
            const rateId = document.getElementById('edit_id').value;
            const response = await fetch(`/admin/parking-rates/${rateId}`, {
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
                showNotification('Tarif parkir berhasil diperbarui');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat memperbarui tarif');
            }
        } catch (error) {
            showNotification(error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Handle delete with SweetAlert2
    document.querySelectorAll('.delete-rate').forEach(button => {
        button.addEventListener('click', async function() {
            const id = this.dataset.id;
            
            const result = await Swal.fire({
                title: 'Anda yakin?',
                text: "Tarif parkir ini akan dihapus secara permanen!",
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
                    // Create form for proper method spoofing
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add method spoofing
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    // Set the action URL
                    form.action = `/admin/parking-rates/${id}`;
                    
                    // Add to body and submit
                    document.body.appendChild(form);
                    
                    // But we'll use fetch instead for ajax
                    const response = await fetch(`/admin/parking-rates/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    // Parse the response
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        data = { success: false, message: 'Server error: Failed to parse response' };
                    }
                    
                    if (response.ok) {
                        await Swal.fire({
                            title: 'Berhasil!',
                            text: data.message || 'Tarif parkir berhasil dihapus',
                            icon: 'success',
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700'
                            }
                        });
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat menghapus tarif');
                    }
                } catch (error) {
                    console.error('Error deleting rate:', error);
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

    // Function to show notification toast
    function showNotification(message, type = 'success') {
        // Remove existing notifications
        document.querySelectorAll('.notification-toast').forEach(toast => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        });
        
        const toast = document.createElement('div');
        toast.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out
                          ${type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-red-500'} 
                          text-white animate-fade-in-down flex items-center`;
        
        // Icon based on notification type
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'warning' ? 'fa-exclamation-triangle' : 
                     'fa-exclamation-circle';
                     
        toast.innerHTML = `
            <div class="flex items-center space-x-2 pr-6">
                <i class="fas ${icon} text-xl"></i>
                <span class="font-medium">${message}</span>
            </div>
            <button class="absolute top-1 right-1 text-white hover:text-gray-200 p-1" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(toast);

        // Add subtle animation
        setTimeout(() => {
            toast.classList.add('shadow-xl');
            toast.style.transform = 'translateY(5px)';
        }, 100);
        
        setTimeout(() => {
            toast.style.transform = '';
        }, 300);

        // Remove the toast after a delay
        const timeout = setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
        
        // Clear timeout if user manually closes the notification
        toast.querySelector('button').addEventListener('click', () => {
            clearTimeout(timeout);
        });
    }

    // Show notification if there's a flash message
    @if(session('success'))
        showNotification("{{ session('success') }}", 'success');
    @endif
    
    @if(session('error'))
        showNotification("{{ session('error') }}", 'error');
    @endif
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
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    /* Shake animation for validation errors */
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    
    @keyframes shake {
        0%, 100% {
            transform: translateX(0);
        }
        10%, 30%, 50%, 70%, 90% {
            transform: translateX(-5px);
        }
        20%, 40%, 60%, 80% {
            transform: translateX(5px);
        }
    }
    
    /* Input highlight effects */
    input:focus, select:focus {
        box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.3);
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

<!-- Include Edit Modal -->
@include('admin.parking_rates._edit_modal')

@endsection
