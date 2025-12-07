<x-app-layout>
    <x-slot name="header-title">Tambah Pengeluaran</x-slot>
    <x-slot name="header-subtitle">Catat pengeluaran baru Anda</x-slot>

    <div class="max-w-2xl mx-auto py-6">
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-xl relative overflow-hidden">
            
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-[#ff6b00] opacity-5 blur-3xl pointer-events-none"></div>

            <div class="mb-6 relative z-10">
                <a href="{{ route('expenses.index') }}" class="inline-flex items-center text-[#ff6b00] hover:text-[#ff8c42] transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>

            <h2 class="text-2xl font-bold text-white mb-6 relative z-10">Form Pengeluaran</h2>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-[#ff6b00]/10 border-l-4 border-[#ff6b00] text-white">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-[#ff6b00] mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6 relative z-10">
                @csrf

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-money-bill-wave text-[#ff6b00] mr-2"></i>Nominal (Rp)
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-semibold group-focus-within:text-[#ff6b00] transition-colors">Rp</span>
                        </div>
                        {{-- PENTING: Gunakan type="text" dan inputmode="numeric" agar format ribuan JS berfungsi --}}
                        <input 
                            type="text" 
                            inputmode="numeric"
                            id="amount" 
                            name="amount" 
                            required
                            class="w-full pl-12 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-[#ff6b00] focus:ring-1 focus:ring-[#ff6b00] transition-all shadow-inner"
                            placeholder="0"
                            value="{{ old('amount') }}"
                        >
                    </div>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-tag text-[#ff6b00] mr-2"></i>Kategori
                    </label>
                    <div class="relative">
                        <select 
                            id="category" 
                            name="category"
                            required
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-[#ff6b00] focus:ring-1 focus:ring-[#ff6b00] transition-all appearance-none cursor-pointer"
                        >
                            <option value="" disabled selected>Pilih kategori...</option>
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('category')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-calendar text-[#ff6b00] mr-2"></i>Tanggal Transaksi
                    </label>
                    <input 
                        type="date" 
                        id="date" 
                        name="date"
                        required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-[#ff6b00] focus:ring-1 focus:ring-[#ff6b00] transition-all"
                        value="{{ old('date', date('Y-m-d')) }}"
                        style="color-scheme: dark;" {{-- Agar icon kalender bawaan browser jadi putih --}}
                    >
                    @error('date')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-file-alt text-[#ff6b00] mr-2"></i>Keterangan / Catatan
                    </label>
                    <textarea 
                        id="description" 
                        name="description"
                        rows="3"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-[#ff6b00] focus:ring-1 focus:ring-[#ff6b00] transition-all resize-none"
                        placeholder="Contoh: Makan siang di warteg..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="activity_id" class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-map-marker-alt text-[#ff6b00] mr-2"></i>Hubungkan ke Aktivitas (Opsional)
                    </label>
                    <div class="relative">
                        <select 
                            id="activity_id" 
                            name="activity_id"
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:outline-none focus:border-[#ff6b00] focus:ring-1 focus:ring-[#ff6b00] transition-all appearance-none cursor-pointer"
                        >
                            <option value="">Tidak ada aktivitas terkait</option>
                            @foreach($recentActivities as $activity)
                                <option value="{{ $activity->id }}" {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                                    {{ $activity->title }} ({{ $activity->date_start ? $activity->date_start->format('d M') : '-' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-700 mt-8">
                    <button type="button" onclick="window.location.href='{{ route('expenses.index') }}'" 
                        class="px-6 py-3 border border-gray-600 rounded-xl text-gray-400 hover:text-white hover:border-gray-400 hover:bg-gray-700 transition duration-200">
                        Batal
                    </button>
                    
                    <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-[#ff6b00] to-[#ff8c42] text-white font-bold rounded-xl hover:shadow-[0_0_20px_rgba(255,107,0,0.4)] transform hover:-translate-y-1 transition duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-lightbulb text-yellow-400 mr-2"></i> Tips Cepat
            </h3>
            <ul class="space-y-3 text-gray-400 text-sm">
                <li class="flex items-start">
                    <i class="fas fa-check text-[#ff6b00] mr-3 mt-1"></i>
                    <span>Tulis deskripsi yang jelas agar mudah dicari kembali.</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-[#ff6b00] mr-3 mt-1"></i>
                    <span>Hubungkan pengeluaran dengan "Aktivitas" jika Anda sedang dalam perjalanan dinas/liburan.</span>
                </li>
            </ul>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const amountInput = document.getElementById('amount');
            const dateInput = document.getElementById('date');

            // 1. Logic Format Ribuan (Auto Currency)
            if (amountInput) {
                amountInput.addEventListener('input', function(e) {
                    // Hapus semua karakter kecuali angka
                    let value = e.target.value.replace(/[^\d]/g, '');
                    
                    if (value) {
                        // Tambahkan titik setiap 3 digit
                        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        e.target.value = value;
                    }
                });
            }

            // 2. Set Max Date ke Hari Ini
            if (dateInput) {
                dateInput.max = new Date().toISOString().split("T")[0];
            }
        });
    </script>
    @endpush
</x-app-layout>