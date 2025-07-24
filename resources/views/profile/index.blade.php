@extends('layouts.app')

@section('title', 'Profile - SmartPark')

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
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

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const currentPasswordInput = document.getElementById('current_password');

    // Password validation
    function validatePassword() {
        if (passwordInput.value || confirmPasswordInput.value || currentPasswordInput.value) {
            if (!currentPasswordInput.value) {
                currentPasswordInput.setCustomValidity('Current password is required when changing password');
            } else {
                currentPasswordInput.setCustomValidity('');
            }
            
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Passwords do not match');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        } else {
            currentPasswordInput.setCustomValidity('');
            confirmPasswordInput.setCustomValidity('');
        }
    }

    passwordInput.addEventListener('input', validatePassword);
    confirmPasswordInput.addEventListener('input', validatePassword);
    currentPasswordInput.addEventListener('input', validatePassword);

    // Form submission
    form.addEventListener('submit', function(e) {
        validatePassword();
        if (!form.checkValidity()) {
            e.preventDefault();
            // Highlight all invalid fields
            const invalidFields = form.querySelectorAll(':invalid');
            invalidFields.forEach(field => {
                field.classList.add('border-red-500', 'animate-shake');
                setTimeout(() => field.classList.remove('animate-shake'), 500);
            });
        }
    });

    // Clear validation styling on input
    form.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });
    });

    // Avatar upload handling
    const avatarUpload = document.getElementById('avatar-upload');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarModal = document.getElementById('avatar-preview-modal');
    const cancelAvatar = document.getElementById('cancel-avatar');
    const saveAvatar = document.getElementById('save-avatar');
    const currentAvatar = document.getElementById('current-avatar');
    let originalFile = null;

    function showModal() {
        avatarModal.style.opacity = '1';
        avatarModal.style.pointerEvents = 'auto';
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            avatarModal.querySelector('.transform').classList.remove('scale-95');
            avatarModal.querySelector('.transform').classList.add('scale-100');
        }, 10);
    }

    function hideModal() {
        avatarModal.querySelector('.transform').classList.remove('scale-100');
        avatarModal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            avatarModal.style.opacity = '0';
            avatarModal.style.pointerEvents = 'none';
            document.body.style.overflow = '';
        }, 200);
    }

    avatarUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('File terlalu besar. Maksimal ukuran file adalah 2MB.');
                avatarUpload.value = '';
                return;
            }

            if (!file.type.match('image.*')) {
                alert('File harus berupa gambar.');
                avatarUpload.value = '';
                return;
            }

            originalFile = file;
            const reader = new FileReader();
            
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                showModal();
            }
            
            reader.readAsDataURL(file);
        }
    });

    cancelAvatar.addEventListener('click', function() {
        hideModal();
        avatarUpload.value = '';
    });

    saveAvatar.addEventListener('click', function() {
        if (originalFile) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentAvatar.src = e.target.result;
            }
            reader.readAsDataURL(originalFile);
        }
        hideModal();
    });
});
</script>
@endpush

@push('styles')
<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-shake {
    animation: shake 0.3s ease-in-out;
}
.animate-fadeInDown {
    animation: fadeInDown 0.3s ease-out;
}

/* Improve mobile responsiveness */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-header > div:last-child {
        margin-left: 0;
        margin-top: 1rem;
    }
}

.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}
</style>
@endpush

@section('content')
<div class="min-h-screen py-12 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Card -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-8">
                    <div class="flex flex-col sm:flex-row items-center">
                        <div class="relative group">
                            <img src="{{ Auth::user()->avatar_url }}" 
                                 alt="Profile" 
                                 id="current-avatar"
                                 class="w-24 h-24 rounded-full border-4 border-white object-cover shadow-lg">
                            <label for="avatar-upload" class="absolute bottom-0 right-0 bg-white w-8 h-8 rounded-full shadow-lg 
                                                            cursor-pointer group-hover:scale-110 transition-all duration-200
                                                            flex items-center justify-center hover:bg-blue-50">
                                <i class="fas fa-camera text-blue-600 text-sm"></i>
                            </label>
                        </div>
                        <div class="ml-0 sm:ml-6 mt-4 sm:mt-0 text-center sm:text-left">
                            <h1 class="text-2xl font-bold text-white">{{ Auth::user()->name }}</h1>
                            <p class="text-blue-100">Member since {{ Auth::user()->created_at->format('F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Form -->
                <div class="p-6">
                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-lg bg-green-100 dark:bg-green-800 border border-green-200 dark:border-green-700
                                  animate-fadeInDown flex items-center justify-between alert alert-success">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl mr-3"></i>
                                <p class="text-green-700 dark:text-green-200">{{ session('success')['message'] }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 rounded-lg bg-red-100 dark:bg-red-800 border border-red-200 dark:border-red-700
                                  animate-fadeInDown flex items-center justify-between alert alert-danger">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-xl mr-3"></i>
                                <p class="text-red-700 dark:text-red-200">{{ session('error')['message'] }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="file" id="avatar-upload" name="avatar" class="hidden" accept="image/*">

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                                    class="pl-10 h-12 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 
                                           dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base"
                                    placeholder="Masukkan nama lengkap">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                    class="pl-10 h-12 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 
                                           dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base"
                                    placeholder="contoh@email.com">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Telepon</label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}"
                                    class="pl-10 h-12 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 
                                           dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base"
                                    placeholder="0812-3456-7890">
                            </div>
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Change Password Section -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ubah Password</h3>                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Saat Ini</label>
                                <div class="mt-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <input type="password" id="current_password" name="current_password"
                                        class="pl-10 pr-10 h-12 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                               dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base"
                                        placeholder="Masukkan password saat ini">
                                    <button type="button" onclick="togglePassword('current_password')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="mt-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru</label>
                                <div class="mt-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password" name="password"
                                        class="pl-10 pr-10 h-12 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 
                                               dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base"
                                        placeholder="Masukkan password baru">
                                    <button type="button" onclick="togglePassword('password')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm New Password -->
                            <div class="mt-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                                <div class="mt-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="pl-10 pr-10 h-12 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 
                                               dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base"
                                        placeholder="Konfirmasi password baru">
                                    <button type="button" onclick="togglePassword('password_confirmation')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-eye text-gray-400 hover:text-gray-600 cursor-pointer"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-6">
                            <button type="submit" class="btn-modern">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Preview Modal -->
<div id="avatar-preview-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-lg w-full transform transition-transform duration-300 scale-95">
            <div class="text-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Preview Avatar</h3>
            </div>
            <div class="relative aspect-square w-full mb-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <img id="avatar-preview" class="w-full h-full object-cover rounded-lg" src="#" alt="Avatar preview">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancel-avatar" 
                        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200">
                    Cancel
                </button>
                <button type="submit" id="save-avatar" class="btn-modern">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
