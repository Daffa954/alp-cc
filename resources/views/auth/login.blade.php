@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="bg-white card-shadow rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-slate-800 text-center mb-6">Masuk ke Akun Anda</h2>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                <i class="fas fa-envelope mr-2 text-blue-500"></i>Alamat Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none @error('email') border-red-300 @enderror"
                placeholder="nama@email.com"
            >
        </div>

        <!-- Password -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-medium text-slate-700">
                    <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500 transition">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none @error('password') border-red-300 @enderror"
                    placeholder="••••••••"
                >
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                <span class="ml-2 text-sm text-slate-600">Ingat saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full btn-primary text-white font-semibold py-3 px-4 rounded-xl mb-6">
            Masuk <i class="fas fa-sign-in-alt ml-2"></i>
        </button>

        <!-- Divider -->
        
        <!-- Social Login (Optional) -->
        

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-500 transition">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Tambah animasi pada form
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.classList.add('animate__animated', 'animate__fadeInUp');
    });
</script>
@endsection