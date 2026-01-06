<x-app-layout>
    <x-slot name="header-title">Edit Pengeluaran</x-slot>
    <x-slot name="header-subtitle">Perbarui data belanja atau tagihan</x-slot>

    <div class="max-w-2xl mx-auto py-6">
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-xl relative">

            {{-- Tombol Batal --}}
            <div class="mb-6">
                <a href="{{ route('expenses.index') }}"
                    class="inline-flex items-center text-[#ff6b00] hover:text-[#ff8c42] transition">
                    <i class="fas fa-arrow-left mr-2"></i> Batal
                </a>
            </div>

            <form method="POST" action="{{ route('expenses.update', $expense->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi Pengeluaran</label>
                    <input type="text" name="description" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00] placeholder-gray-500"
                        placeholder="Contoh: Nasi Goreng, Bensin, Listrik"
                        value="{{ old('description', $expense->description) }}">
                </div>

                {{-- TAMBAHAN: Input Activity --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Aktivitas (Opsional)</label>
                    <div class="relative">
                        <select name="activity_id"
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00] appearance-none cursor-pointer">
                            <option value="">-- Tidak Terkait Aktivitas --</option>
                            
                            {{-- Loop data activity yang dikirim dari controller --}}
                            @if(isset($recentActivities))
                                @foreach($recentActivities as $activity)
                                    <option value="{{ $activity->id }}" 
                                        {{ old('activity_id', $expense->activity_id) == $activity->id ? 'selected' : '' }}>
                                        {{ $activity->title }} 
                                        ({{ \Carbon\Carbon::parse($activity->date_start)->format('d M') }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">*Hubungkan pengeluaran ini dengan kegiatan perjalanan/acara tertentu.</p>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                    <div class="relative">
                        <select name="category" required
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00] appearance-none cursor-pointer">
                            <option value="" disabled>Pilih Kategori</option>
                            @php
                                $cats = $categories ?? [
                                    'Food & Dining' => 'Makan & Minum',
                                    'Transportation' => 'Transportasi', 
                                    'Shopping' => 'Belanja', 
                                    'Housing & Utilities' => 'Tagihan & Utilitas', 
                                    'Entertainment' => 'Hiburan', 
                                    'Health & Medical' => 'Kesehatan', 
                                    'Other' => 'Lainnya'
                                ];
                            @endphp
                            
                            @foreach($cats as $key => $label)
                                <option value="{{ $key }}" 
                                    {{ (old('category', $expense->category) == $key || old('category', $expense->category) == $label) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Jumlah (Rp) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-semibold">Rp</span>
                        </div>
                        <input type="text" id="amount" name="amount" inputmode="numeric" required
                            class="w-full pl-12 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white font-bold focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                            value="{{ old('amount', number_format($expense->amount, 0, ',', '.')) }}">
                    </div>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Transaksi</label>
                    <input type="date" name="date" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                        value="{{ old('date', $expense->date ? \Carbon\Carbon::parse($expense->date)->format('Y-m-d') : '') }}"
                        style="color-scheme: dark;">
                </div>

                {{-- Tombol Submit --}}
                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('amount').addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value) {
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    e.target.value = value;
                }
            });
        </script>
    @endpush
</x-app-layout>