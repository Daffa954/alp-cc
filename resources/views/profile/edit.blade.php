<x-app-layout>
    <x-slot name="header-title">Pengaturan Akun</x-slot>
    <x-slot name="header-subtitle">Kelola informasi profil dan keamanan akun Anda</x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        {{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
        {{-- Tambahkan di atas form atau di sidebar --}}

        <div class="mb-6 p-4 bg-gray-700/50 rounded-lg border border-gray-600">
            <h4 class="font-medium text-gray-300 mb-3 flex items-center">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                Informasi Saat Ini
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-gray-400">Nama:</span>
                    <p class="text-white">{{ $user->name }}</p>
                </div>
                <div>
                    <span class="text-gray-400">Email:</span>
                    <p class="text-white">{{ $user->email }}</p>
                </div>
                @if ($user->job)
                    <div>
                        <span class="text-gray-400">Pekerjaan:</span>
                        <p class="text-white">{{ $user->job }}</p>
                    </div>
                @endif
                @if ($user->job_location)
                    <div>
                        <span class="text-gray-400">Lokasi Kerja:</span>
                        <p class="text-white">{{ $user->job_location }}</p>
                    </div>
                @endif
                @if ($user->address)
                    <div class="md:col-span-2">
                        <span class="text-gray-400">Alamat:</span>
                        <p class="text-white">{{ $user->address }}</p>
                    </div>
                @endif
            </div>
        </div>
        {{-- GRID LAYOUT: 2 Kolom di Desktop --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- 1. UPDATE PROFILE INFORMATION --}}
            <div class="p-6 sm:p-8 bg-gray-800 border border-gray-700 shadow-xl rounded-2xl h-full">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center mr-3">
                        <i class="fas fa-user-circle text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Informasi Profil</h3>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 2. UPDATE PASSWORD --}}
            <div class="p-6 sm:p-8 bg-gray-800 border border-gray-700 shadow-xl rounded-2xl h-full">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-orange-500/10 flex items-center justify-center mr-3">
                        <i class="fas fa-lock text-[#ff6b00] text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Ganti Password</h3>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>



        {{-- 3. DELETE USER (DANGER ZONE) --}}
        <div class="p-6 sm:p-8 bg-red-900/10 border border-red-500/30 shadow-xl rounded-2xl">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-400">Hapus Akun</h3>
                    <p class="text-sm text-gray-400">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>
