<x-app-layout>
    <x-slot name="header-title">Daftar Aktivitas</x-slot>
    <x-slot name="header-subtitle">Riwayat perjalanan dan kegiatan Anda</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-white">Semua Aktivitas</h2>
                <a href="{{ route('activities.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#ff6b00] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#ff8c42] transition shadow-lg shadow-orange-500/30">
                    <i class="fas fa-plus mr-2"></i> Tambah Aktivitas
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 rounded-xl bg-green-900/50 border border-green-500 text-green-200">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-gray-800 overflow-hidden shadow-xl rounded-xl border border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Judul & Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Transport</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Biaya</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($activities as $activity)
                                <tr class="hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $activity->date_start ? $activity->date_start->format('d M Y, H:i') : '-' }}
                                        @if($activity->date_end)
                                            <br><span class="text-xs text-gray-500">s/d {{ $activity->date_end->format('H:i') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-white">{{ $activity->title }}</div>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $activity->activity_location ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        <span class="px-2 py-1 bg-gray-700 rounded text-xs">
                                            {{ ucfirst($activity->transportation) ?? '-' }}
                                        </span>
                                        @if($activity->distance_in_km)
                                            <span class="text-xs text-gray-500 ml-1">({{ $activity->distance_in_km }} km)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-[#ff6b00]">
                                        Rp {{ number_format($activity->cost_to_there, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center space-x-3">
                                            <a href="{{ route('activities.edit', $activity->id) }}" class="text-blue-400 hover:text-blue-300">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Hapus aktivitas ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada aktivitas yang dicatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-700">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>