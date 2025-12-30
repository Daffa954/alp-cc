<x-app-layout>
    <x-slot name="header-title">Pengaturan Akun</x-slot>
    <x-slot name="header-subtitle">Kelola informasi profil dan keamanan akun Anda</x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

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