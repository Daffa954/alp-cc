@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
<div class="bg-white card-shadow rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-slate-800 text-center mb-6">Buat Akun Baru</h2>

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

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                <i class="fas fa-user mr-2 text-[#ff6b00]"></i>Nama Lengkap
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none"
                placeholder="John Doe"
            >
        </div>

        <!-- Email -->
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                <i class="fas fa-envelope mr-2 text-[#ff6b00]"></i>Alamat Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none"
                placeholder="nama@email.com"
            >
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                <i class="fas fa-lock mr-2 text-[#ff6b00]"></i>Password
            </label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none"
                    placeholder="Minimal 8 karakter"
                >
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                <p>• Minimal 8 karakter</p>
                <p>• Kombinasi huruf dan angka</p>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                <i class="fas fa-lock mr-2 text-[#ff6b00]"></i>Konfirmasi Password
            </label>
            <div class="relative">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none"
                    placeholder="Ulangi password"
                >
                <button type="button" onclick="toggleConfirmPassword()" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-eye" id="toggleConfirmIcon"></i>
                </button>
            </div>
        </div>

        <!-- Optional Fields -->
        <div class="mb-6">
            <h3 class="text-sm font-medium text-slate-700 mb-3">
                <i class="fas fa-info-circle mr-2 text-[#ff6b00]"></i>Informasi Tambahan (Opsional)
            </h3>
            
            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="block text-sm text-slate-600 mb-2">Alamat</label>
                <textarea
                    id="address"
                    name="address"
                    rows="2"
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none"
                    placeholder="Alamat lengkap"
                >{{ old('address') }}</textarea>
            </div>

            <!-- Job -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="job" class="block text-sm text-slate-600 mb-2">Pekerjaan</label>
                    <input
                        type="text"
                        id="job"
                        name="job"
                        value="{{ old('job') }}"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none"
                        placeholder="Posisi pekerjaan"
                    >
                </div>
                <div>
                    <label for="job_location" class="block text-sm text-slate-600 mb-2">Lokasi Kerja</label>
                    <input
                        type="text"
                        id="job_location"
                        name="job_location"
                        value="{{ old('job_location') }}"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl input-focus transition duration-200 focus:outline-none"
                        placeholder="Kota"
                    >
                </div>
            </div>
        </div>

        <!-- Terms and Conditions -->
        {{-- <div class="mb-6">
            <label class="flex items-start">
                <input type="checkbox" name="terms" required class="mt-1 h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                <span class="ml-2 text-sm text-slate-600">
                    Saya setuju dengan
                    <a href="#" class="text-blue-600 hover:text-blue-500">Syarat & Ketentuan</a>
                    dan
                    <a href="#" class="text-blue-600 hover:text-blue-500">Kebijakan Privasi</a>
                </span>
            </label>
        </div> --}}

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-[#ff720c] hover:bg-[#fb6602] text-white font-semibold py-3 px-4 rounded-xl mb-6">
            Buat Akun <i class="fas fa-user-plus ml-2"></i>
        </button>

        <!-- Divider -->
        <div class="relative mb-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-slate-500">Atau daftar dengan</span>
            </div>
        </div>

        <!-- Social Register -->
        
        <!-- Login Link -->
        <div class="text-center">
            <p class="text-sm text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-[#ff911b] hover:text-[#fb6602] transition">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</div>

<!-- Progress Steps -->
<div class="mt-8">
    <div class="flex items-center justify-center">
        <div class="flex items-center">
            <div class="bg-[#ff6b00] w-8 h-8 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-white text-xs"></i>
            </div>
            <div class="w-16 h-1 bg-[#f9b17e] mx-2"></div>
            <div class="w-8 h-8 rounded-full border-2 border-[#f9b17e] flex items-center justify-center">
                <span class="text-xs text-[#ff9b53]">2</span>
            </div>
            <div class="w-16 h-1 bg-[#f9b17e] mx-2"></div>
            <div class="w-8 h-8 rounded-full border-2 border-[#f9b17e] flex items-center justify-center">
                <span class="text-xs text-[#ff9b53]">3</span>
            </div>
        </div>
    </div>
    <div class="flex justify-between mt-2 text-xs text-slate-500">
        <span>Informasi</span>
        <span>Verifikasi</span>
        <span>Selesai</span>
    </div>
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

    function toggleConfirmPassword() {
        const confirmInput = document.getElementById('password_confirmation');
        const toggleIcon = document.getElementById('toggleConfirmIcon');
        
        if (confirmInput.type === 'password') {
            confirmInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            confirmInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Validasi real-time password match
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirm = this.value;
        const button = document.querySelector('button[type="submit"]');
        
        if (confirm && password !== confirm) {
            this.classList.add('border-red-300');
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            this.classList.remove('border-red-300');
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });
</script>
@endsection