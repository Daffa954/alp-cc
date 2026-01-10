<x-app-layout>
    <x-slot name="header-title">Edit Pemasukan</x-slot>
    <x-slot name="header-subtitle">Perbarui data pendapatan</x-slot>

    <div class="max-w-2xl mx-auto py-6">
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-xl relative">

            <div class="mb-6">
                <a href="{{ route('incomes.index') }}"
                    class="inline-flex items-center text-[#ff6b00] hover:text-[#ff8c42] transition">
                    <i class="fas fa-arrow-left mr-2"></i> Batal
                </a>
            </div>

            <form method="POST" action="{{ route('incomes.update', ['income' => $income]) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Sumber --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Sumber Pemasukan</label>
                    <input type="text" name="source"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                        value="{{ old('source', $income->source) }}">
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Jumlah (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-semibold">Rp</span>
                        </div>
                        <input type="text" id="amount" name="amount" inputmode="numeric" required
                            class="w-full pl-12 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white font-bold focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                            value="{{ old('amount', number_format($income->amount, 0, ',', '.')) }}">
                    </div>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Diterima</label>
                    <input type="date" name="date_received" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                        value="{{ old('date_received', $income->date_received ? $income->date_received->format('Y-m-d') : '') }}"
                        style="color-scheme: dark;">
                </div>

                {{-- TAMBAHAN: Checkbox Is Regular --}}
                <div class="flex items-center bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                    <input type="checkbox" id="is_regular" name="is_regular" value="1"
                        class="w-5 h-5 text-[#ff6b00] bg-gray-900 border-gray-700 rounded focus:ring-[#ff6b00] focus:ring-offset-gray-800"
                        {{ old('is_regular', $income->is_regular) ? 'checked' : '' }}>
                    <label for="is_regular" class="ml-3 text-sm font-medium text-gray-300 cursor-pointer select-none">
                        Pemasukan Tetap (Gaji bulanan, dll)
                    </label>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00] resize-none">{{ old('notes', $income->notes) }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-500 text-white font-bold rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition">
                        Update Pemasukan
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