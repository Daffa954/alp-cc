<x-app-layout>
    <x-slot name="header-title">Daftar Aktivitas</x-slot>
    <x-slot name="header-subtitle">Riwayat perjalanan dan kegiatan Anda</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-white">Semua Aktivitas</h2>
                    <p class="text-sm text-gray-400">Dikelompokkan berdasarkan tanggal mulai kegiatan</p>
                </div>
                <a href="{{ route('activities.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-[#ff6b00] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#ff8c42] transition shadow-lg shadow-orange-500/30">
                    <i class="fas fa-plus mr-2"></i> Tambah
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-gas-pump text-5xl text-orange-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Biaya Perjalanan (Bulan Ini)</div>
                    <div class="text-2xl font-bold text-white mt-1">Rp {{ number_format($totalCost, 0, ',', '.') }}</div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-road text-5xl text-blue-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Total Jarak Tempuh</div>
                    <div class="text-2xl font-bold text-white mt-1">{{ number_format($totalKm, 1) }} Km</div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-car text-5xl text-orange-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Transportasi Terfavorit</div>
                    <div class="text-xl font-bold text-white mt-1 truncate">{{ ucfirst($popularTransport->transportation ?? '-') }}</div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                @php
                    $groupedActivities = $activities->getCollection()->groupBy(function($item) {
                        return \Carbon\Carbon::parse($item->date_start)->format('Y-m-d');
                    });
                @endphp

                <div class="mt-4 mb-6 flex items-center justify-center space-x-4">
                    @if ($activities->onFirstPage())
                        <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 text-xs font-bold uppercase">Newest</span>
                    @else
                        <a href="{{ $activities->previousPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase tracking-widest">< Newer</a>
                    @endif

                    @if ($activities->count() > 0)
                        <form action="{{ route('activities.index') }}" method="GET" class="relative">
                            <div onclick="document.getElementById('jump_to_date_activity').showPicker()" 
                                 class="px-6 py-2 bg-gray-800 border border-gray-700 rounded-2xl text-orange-400 font-bold text-sm shadow-xl hover:border-orange-500 hover:bg-gray-750 transition-all cursor-pointer group flex items-center">
                                <i class="far fa-calendar-alt mr-2 group-hover:scale-110 transition-transform"></i>
                                {{ \Carbon\Carbon::parse($activities->first()->date_start)->format('d M') }} — {{ \Carbon\Carbon::parse($activities->last()->date_start)->format('d M Y') }}
                                <input type="date" id="jump_to_date_activity" name="filter_date" class="absolute inset-0 opacity-0 cursor-pointer" onchange="this.form.submit()">
                            </div>
                        </form>
                    @endif

                    @if ($activities->hasMorePages())
                        <a href="{{ $activities->nextPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase tracking-widest">Older ></a>
                    @else
                        <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 text-xs font-bold uppercase">Oldest</span>
                    @endif
                </div>

                @forelse($groupedActivities as $date => $dailyActivities)
                    <div class="bg-gray-800 rounded-3xl border border-gray-700 shadow-xl overflow-hidden mb-6">
                        <div class="bg-gray-750/50 p-5 border-b border-gray-700 flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-500/20 text-orange-400 border border-orange-500/30 text-xs font-bold px-3 py-1 rounded-xl uppercase tracking-wider">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                </div>
                                <h3 class="text-white font-bold text-lg">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Total Biaya Hari Ini</p>
                                <span class="text-orange-400 font-black text-lg">Rp {{ number_format($dailyActivities->sum('cost_to_there'), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="divide-y divide-gray-700/50">
                            @foreach($dailyActivities as $activity)
                                <div class="p-5 hover:bg-gray-700/30 transition-colors group flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-12 w-12 rounded-2xl bg-gray-900/50 flex items-center justify-center text-orange-400 border border-gray-700 group-hover:border-orange-500/50 transition-all">
                                            <i class="fas fa-map-marker-alt text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-white font-semibold group-hover:text-orange-400 transition-colors">{{ $activity->title }}</h4>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $activity->transportation }}</span>
                                                <span class="text-gray-600 text-xs">•</span>
                                                <p class="text-gray-400 text-xs">{{ $activity->activity_location ?? 'Lokasi tidak diset' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right mr-4">
                                            <p class="text-white font-bold text-lg">Rp {{ number_format($activity->cost_to_there, 0, ',', '.') }}</p>
                                            <p class="text-[10px] text-gray-500 font-bold">{{ $activity->distance_in_km }} Km</p>
                                        </div>
                                        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('activities.edit', $activity->id) }}" class="p-2 text-gray-400 hover:text-blue-400"><i class="fas fa-pen text-sm"></i></a>
                                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-400"><i class="fas fa-trash text-sm"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-gray-800 rounded-3xl border-2 border-dashed border-gray-700 text-gray-500">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>