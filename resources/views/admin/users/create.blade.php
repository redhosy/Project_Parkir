@extends('layouts.admin')

@section('title', 'Tambah Pengguna - SmartPark')

@section('content')
<div class="space-y-6">
    <!-- Page Title and Action Button -->
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-6 h-12 flex items-center justify-center mr-2">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Pengguna</h1>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="btn-modern group relative overflow-hidden px-6 py-3 rounded-xl bg-gray-500 hover:bg-gray-600 transform hover:scale-105 transition-all duration-300">
            <span class="relative z-10 flex items-center text-white">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Form Pengguna Baru</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                               placeholder="Nama lengkap pengguna">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                               placeholder="Email pengguna">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nomor Telepon
                        </label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                               placeholder="Nomor telepon pengguna">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Role -->
                    <div>
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pengguna</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                   placeholder="********">
                            <button type="button" onclick="togglePasswordVisibility('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center transition-colors duration-200">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                   placeholder="********">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center transition-colors duration-200">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Avatar -->
                <div>
                    <label for="avatar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Avatar
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="avatar-preview w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            <i class="fas fa-user text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg"
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                JPG, JPEG, or PNG. Maksimal ukuran 2MB.
                            </p>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-200 ease-in-out transform hover:scale-105">
                        <i class="fas fa-user-plus mr-2"></i> Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview avatar when file is selected
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.querySelector('.avatar-preview');
        
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    avatarPreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Avatar Preview">`;
                };
                
                reader.readAsDataURL(this.files[0]);
            } else {
                avatarPreview.innerHTML = `<i class="fas fa-user text-3xl text-gray-400 dark:text-gray-500"></i>`;
            }
        });
    });
    
    // Function to toggle password visibility
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const button = input.parentElement.querySelector('button');
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
