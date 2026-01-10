{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    {{-- Name --}}
    <div class="space-y-2">
        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-300" />
        <x-text-input 
            id="name" 
            name="name" 
            type="text" 
            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white" 
            :value="old('name', $user->name)" 
            required 
            autofocus 
            autocomplete="name" 
            placeholder="Masukkan nama lengkap Anda"
        />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    {{-- Email --}}
    <div class="space-y-2">
        <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
        <x-text-input 
            id="email" 
            name="email" 
            type="email" 
            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white" 
            :value="old('email', $user->email)" 
            required 
            autocomplete="email"
            placeholder="contoh@email.com"
        />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    {{-- Address --}}
    <div class="space-y-2">
        <x-input-label for="address" :value="__('Alamat')" class="text-gray-300" />
        <textarea 
            id="address" 
            name="address" 
            rows="3"
            class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:border-[#ff6b00] focus:ring-[#ff6b00]"
            placeholder="Masukkan alamat lengkap Anda"
        >{{ old('address', $user->address) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>

    {{-- Job & Location --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Job --}}
        <div class="space-y-2">
            <x-input-label for="job" :value="__('Pekerjaan')" class="text-gray-300" />
            <x-text-input 
                id="job" 
                name="job" 
                type="text" 
                class="mt-1 block w-full bg-gray-700 border-gray-600 text-white" 
                :value="old('job', $user->job)" 
                autocomplete="organization"
                placeholder="Posisi / Profesi"
            />
            <x-input-error class="mt-2" :messages="$errors->get('job')" />
        </div>

        {{-- Job Location --}}
        <div class="space-y-2">
            <x-input-label for="job_location" :value="__('Lokasi Kerja')" class="text-gray-300" />
            <x-text-input 
                id="job_location" 
                name="job_location" 
                type="text" 
                class="mt-1 block w-full bg-gray-700 border-gray-600 text-white" 
                :value="old('job_location', $user->job_location)" 
                autocomplete="organization-title"
                placeholder="Kantor / Perusahaan"
            />
            <x-input-error class="mt-2" :messages="$errors->get('job_location')" />
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('status') === 'profile-updated')
        <div class="mt-4 p-3 bg-green-900/30 border border-green-500/50 rounded-lg">
            <p class="text-sm text-green-400 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Profil berhasil diperbarui!
            </p>
        </div>
    @endif

    {{-- Submit Button --}}
    <div class="flex items-center gap-4">
        <x-primary-button class="bg-[#ff6b00] hover:bg-[#e55a00] border-[#ff6b00] focus:ring-[#ff6b00]">
            <i class="fas fa-save mr-2"></i>
            {{ __('Simpan Perubahan') }}
        </x-primary-button>
    </div>
</form>