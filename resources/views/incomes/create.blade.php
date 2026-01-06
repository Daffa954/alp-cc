<x-app-layout>
    <x-slot name="header-title">Tambah Pemasukan</x-slot>
    <x-slot name="header-subtitle">Catat pendapatan baru</x-slot>

    <div class="max-w-2xl mx-auto py-6">
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-xl relative overflow-hidden">

            <div
                class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-green-500 opacity-5 blur-3xl pointer-events-none">
            </div>

            <div class="mb-6 relative z-10">
                <a href="{{ route('incomes.index') }}"
                    class="inline-flex items-center text-[#ff6b00] hover:text-[#ff8c42] transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <form method="POST" action="{{ route('incomes.store') }}" class="space-y-6 relative z-10">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Sumber Pemasukan</label>
                    <input type="text" name="source"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                        placeholder="Contoh: Gaji, Bonus, Freelance" value="{{ old('source') }}">
                    @error('source')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-semibold">Rp</span>
                        </div>
                        <input type="text" id="amount" name="amount" inputmode="numeric" required
                            class="w-full pl-12 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white font-bold focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                            placeholder="0" value="{{ old('amount') }}">
                    </div>
                    @error('amount')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Diterima</label>
                    <input type="date" name="date_received" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                        value="{{ old('date_received', date('Y-m-d')) }}" style="color-scheme: dark;">
                    @error('date_received')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_regular" value="1"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        <span class="ml-2 text-gray-200">Ini adalah Gaji Tetap / Pemasukan Rutin</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-6">Centang jika ini adalah pendapatan yang pasti Anda terima
                        setiap bulan (Gaji, Uang Saku Bulanan).</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00] resize-none">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-500 text-white font-bold rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition">
                        Simpan Pemasukan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Script Format Ribuan
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
