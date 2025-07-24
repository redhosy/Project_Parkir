@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - SmartPark')

@section('content')
<div class="space-y-6">
    <!-- Page Title and Action Button -->
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-6 h-12 flex items-center justify-center mr-2">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Pengguna</h1>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="btn-modern group relative overflow-hidden px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-300">
            <span class="relative z-10 flex items-center text-white">
                <i class="fas fa-user-plus mr-2 transition-transform group-hover:rotate-180 duration-300"></i>
                Tambah Pengguna
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></div>
        </a>
    </div>

    <!-- Main Content -->
    <div>
        <!-- User Management -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Daftar Pengguna</h2>
                
                <!-- Search Bar -->
                <div class="relative w-64">
                    <input type="text" id="searchUser" placeholder="Cari pengguna..." 
                           class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg py-2 pl-10 pr-4 w-full text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:border-purple-500 dark:focus:border-purple-400">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pengguna
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No. Telepon
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="usersTableBody">
                        @forelse ($users as $user)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50" data-search="{{ $user->name }} {{ $user->email }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $user->phone_number ?: '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleClass = $user->isAdmin() 
                                            ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100'
                                            : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleClass }}">
                                        {{ $user->getRoleLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                class="delete-user text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200"
                                                data-id="{{ $user->id }}" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users text-4xl mb-3"></i>
                                        <p class="text-sm">Belum ada data pengguna.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchUser').addEventListener('input', function(e) {
        const searchText = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTableBody tr');
        
        rows.forEach(row => {
            const searchData = row.getAttribute('data-search').toLowerCase();
            if (searchData.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Delete user functionality
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', async function() {
            if (this.disabled) return;
            
            const userId = this.dataset.id;
            
            const result = await Swal.fire({
                title: 'Anda yakin?',
                text: "Pengguna ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                buttonsStyling: true,
                customClass: {
                    confirmButton: 'bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 mr-2',
                    cancelButton: 'bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600'
                }
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        Swal.fire(
                            'Terhapus!',
                            'Pengguna berhasil dihapus.',
                            'success'
                        );
                        // Reload page after successful deletion
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        const data = await response.json();
                        throw new Error(data.message || 'Terjadi kesalahan saat menghapus pengguna');
                    }
                } catch (error) {
                    Swal.fire(
                        'Error!',
                        error.message,
                        'error'
                    );
                }
            }
        });
    });

    // Show notification if there's a flash message
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            timerProgressBar: true
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
        });
    @endif
});
</script>
@endpush
