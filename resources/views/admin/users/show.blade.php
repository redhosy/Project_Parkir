@extends('layouts.admin')

@section('title', 'Detail Pengguna - SmartPark')

@section('content')
<div class="space-y-6">
    <!-- Page Title and Action Button -->
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-6 h-12 flex items-center justify-center mr-2">
                <i class="fas fa-user text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Pengguna</h1>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user->id) }}"
            class="btn-modern group relative overflow-hidden px-6 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 transform hover:scale-105 transition-all duration-300">
                <span class="relative z-10 flex items-center text-white">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </span>
            </a>
            <a href="{{ route('admin.users.index') }}"
            class="btn-modern group relative overflow-hidden px-6 py-3 rounded-xl bg-gray-500 hover:bg-gray-600 transform hover:scale-105 transition-all duration-300">
                <span class="relative z-10 flex items-center text-white">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Profil Pengguna</h2>
            </div>
            <div class="p-6 flex flex-col items-center text-center">
                <div class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-white dark:border-gray-600 shadow-lg">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $user->name }}</h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' }} mb-4">
                    {{ $user->getRoleLabel() }}
                </span>
                
                <div class="w-full mt-4 space-y-3">
                    <div class="flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                        <div class="flex-shrink-0 w-10 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="flex-1 text-gray-800 dark:text-gray-200 text-left">
                            {{ $user->email }}
                        </div>
                    </div>
                    <div class="flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                        <div class="flex-shrink-0 w-10 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="flex-1 text-gray-800 dark:text-gray-200 text-left">
                            {{ $user->phone_number ?: 'Tidak ada' }}
                        </div>
                    </div>
                    <div class="flex items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                        <div class="flex-shrink-0 w-10 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="flex-1 text-gray-800 dark:text-gray-200 text-left">
                            Terdaftar: {{ $user->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="flex-1 text-gray-800 dark:text-gray-200 text-left">
                            Diperbarui: {{ $user->updated_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity and Stats -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 h-full">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Aktivitas & Statistik</h2>
                </div>
                <div class="p-6">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Booking</p>
                                    <h4 class="text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1">{{ $user->bookings->count() }}</h4>
                                </div>
                                <div class="bg-blue-100 dark:bg-blue-800 p-3 rounded-lg">
                                    <i class="fas fa-ticket-alt text-blue-500 dark:text-blue-300 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-100 dark:border-green-800">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-green-700 dark:text-green-300">Status Akun</p>
                                    <h4 class="text-xl font-bold text-green-900 dark:text-green-100 mt-1">Aktif</h4>
                                </div>
                                <div class="bg-green-100 dark:bg-green-800 p-3 rounded-lg">
                                    <i class="fas fa-check-circle text-green-500 dark:text-green-300 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Bookings -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Booking Terbaru</h3>
                        
                        @if($user->bookings->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase">
                                        <tr>
                                            <th class="px-4 py-3 text-left">ID</th>
                                            <th class="px-4 py-3 text-left">Slot Parkir</th>
                                            <th class="px-4 py-3 text-left">Tanggal</th>
                                            <th class="px-4 py-3 text-left">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($user->bookings->sortByDesc('created_at')->take(5) as $booking)
                                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $booking->id }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $booking->parkingSlot->slot_name ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $booking->created_at->format('d M Y H:i') }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @php
                                                        $statusClass = match($booking->status) {
                                                            'reserved' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
                                                            'active' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
                                                            'completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
                                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
                                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100'
                                                        };
                                                        
                                                        $statusText = match($booking->status) {
                                                            'reserved' => 'Dipesan',
                                                            'active' => 'Aktif',
                                                            'completed' => 'Selesai',
                                                            'cancelled' => 'Dibatalkan',
                                                            default => 'Tidak diketahui'
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($user->bookings->count() > 5)
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Menampilkan 5 dari {{ $user->bookings->count() }} booking.
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-6 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-ticket-alt text-gray-400 dark:text-gray-500 text-3xl mb-2"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Pengguna belum memiliki booking.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
