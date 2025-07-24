@extends('layouts.app')

@section('title', 'Login - SmartPark')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-md w-full">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center transform rotate-12 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-parking text-white text-3xl"></i>
                </div>
            </div>
            <h2 class="text-4xl font-extrabold text-gradient mb-2">Welcome Back</h2>
            <p class="text-gray-600 dark:text-gray-400">Please login to continue using SmartPark</p>
        </div>

        <!-- Login Form -->
        <div class="card-modern">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                   text-gray-900 dark:text-white placeholder-gray-500 
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   bg-white dark:bg-gray-800 transition-all duration-200"
                            placeholder="Enter your email">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="block w-full pl-10 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                                   text-gray-900 dark:text-white placeholder-gray-500
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   bg-white dark:bg-gray-800 transition-all duration-200"
                            placeholder="Enter your password">
                        <button type="button" onclick="togglePasswordVisibility('password')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center transition-colors duration-200">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                            Remember me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" 
                            class="btn-modern w-full flex justify-center py-3 px-4">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Don't have an account? 
                        <a href="{{ route('register') }}" 
                           class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                            Register now
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Social Proof -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Protected by SSL. Your data is secure and encrypted.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
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

    // Add floating animation to the logo
    document.addEventListener('DOMContentLoaded', function() {
        const loginCard = document.querySelector('.card-modern');
        loginCard.classList.add('fade-in');
        
        // Add smooth transition for input fields
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('transform', 'scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('transform', 'scale-105');
            });
        });
    });
</script>
@endpush
@endsection
