<x-app-layout>
    <x-slot name="header-title">Edit Aktivitas</x-slot>
    <x-slot name="header-subtitle">Perbarui data kegiatan</x-slot>

    <div class="max-w-3xl mx-auto py-6">
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-xl">

            <div class="mb-6">
                <a href="{{ route('activities.index') }}"
                    class="inline-flex items-center text-[#ff6b00] hover:text-[#ff8c42] transition">
                    <i class="fas fa-arrow-left mr-2"></i> Batal
                </a>
            </div>

            <form method="POST" action="@route('activities.update', $activity)" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Judul Aktivitas</label>
                        <input type="text" name="title" required
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                            value="{{ old('title', $activity->title) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Lokasi</label>
                        <input type="text" name="activity_location"
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                            value="{{ old('activity_location', $activity->activity_location) }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Waktu Mulai</label>
                        <input type="datetime-local" name="date_start" required
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                            value="{{ old('date_start', $activity->date_start ? $activity->date_start->format('Y-m-d\TH:i') : '') }}"
                            style="color-scheme: dark;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Waktu Selesai</label>
                        <input type="datetime-local" name="date_end"
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                            value="{{ old('date_end', $activity->date_end ? $activity->date_end->format('Y-m-d\TH:i') : '') }}"
                            style="color-scheme: dark;">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Transportasi</label>
                        <select name="transportation"
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]">
                            <option value="">Pilih Transportasi...</option>
                            <option value="motor"
                                {{ old('transportation', $activity->transportation) == 'motor' ? 'selected' : '' }}>
                                Motor Pribadi</option>
                            <option value="mobil"
                                {{ old('transportation', $activity->transportation) == 'mobil' ? 'selected' : '' }}>
                                Mobil Pribadi</option>
                            <option value="ojol"
                                {{ old('transportation', $activity->transportation) == 'ojol' ? 'selected' : '' }}>Ojek
                                Online</option>
                            <option value="umum"
                                {{ old('transportation', $activity->transportation) == 'umum' ? 'selected' : '' }}>
                                Angkutan Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Biaya Perjalanan (Rp)</label>
                        <input type="text" id="cost" name="cost_to_there" inputmode="numeric"
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white focus:border-[#ff6b00] focus:ring-[#ff6b00]"
                            value="{{ old('cost_to_there', number_format($activity->cost_to_there, 0, ',', '.')) }}">
                    </div>
                </div>

                <div class="p-4 bg-gray-700/30 rounded-xl border border-gray-700">
                    <h4 class="text-sm font-bold text-gray-400 mb-3 uppercase">Detail Koordinat</h4>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <input type="number" step="any" name="start_latitude" placeholder="Start Lat"
                            class="bg-gray-900 border-gray-700 rounded-lg text-white text-sm"
                            value="{{ $activity->start_latitude }}">
                        <input type="number" step="any" name="start_longitude" placeholder="Start Long"
                            class="bg-gray-900 border-gray-700 rounded-lg text-white text-sm"
                            value="{{ $activity->start_longitude }}">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <input type="number" step="any" name="end_latitude" placeholder="End Lat"
                            class="bg-gray-900 border-gray-700 rounded-lg text-white text-sm"
                            value="{{ $activity->end_latitude }}">
                        <input type="number" step="any" name="end_longitude" placeholder="End Long"
                            class="bg-gray-900 border-gray-700 rounded-lg text-white text-sm"
                            value="{{ $activity->end_longitude }}">
                    </div>
                    <input type="number" step="0.01" name="distance_in_km" placeholder="Jarak (KM)"
                        class="w-full bg-gray-900 border-gray-700 rounded-lg text-white text-sm"
                        value="{{ $activity->distance_in_km }}">
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-[#ff6b00] to-[#ff8c42] text-white font-bold rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition">
                        Update Aktivitas
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('cost').addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value) {
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    e.target.value = value;
                }
            });
        </script>
    @endpush
</x-app-layout>
