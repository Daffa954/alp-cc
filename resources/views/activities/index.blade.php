<x-app-layout>
    <x-slot name="header-title">Daftar Aktivitas</x-slot>
    <x-slot name="header-subtitle">Riwayat perjalanan dan pengeluaran terkait</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-white">Semua Aktivitas</h2>
                    <p class="text-sm text-gray-400">Dikelompokkan berdasarkan tanggal</p>
                </div>
                <a href="{{ route('activities.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-[#ff6b00] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#ff8c42] transition shadow-lg shadow-orange-500/30">
                    <i class="fas fa-plus mr-2"></i> Tambah
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div
                    class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-coins text-5xl text-orange-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Total Transport (Bulan Ini)</div>
                    <div class="text-2xl font-bold text-white mt-1">Rp {{ number_format($totalCost, 0, ',', '.') }}
                    </div>
                </div>

                <div
                    class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-road text-5xl text-blue-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Total Jarak Tempuh</div>
                    <div class="text-2xl font-bold text-white mt-1">{{ number_format($totalKm, 1) }} Km</div>
                </div>

                <div
                    class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-car text-5xl text-orange-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Transportasi Terfavorit</div>
                    <div class="text-xl font-bold text-white mt-1 truncate">
                        {{ ucfirst($popularTransport->transportation ?? '-') }}</div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                @php
                    $groupedActivities = $activities->getCollection()->groupBy(function ($item) {
                        return \Carbon\Carbon::parse($item->date_start)->format('Y-m-d');
                    });
                @endphp

                <div class="mt-4 mb-6 flex items-center justify-center space-x-4">
                    @if ($activities->onFirstPage())
                        <span
                            class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 text-xs font-bold uppercase">Newest</span>
                    @else
                        <a href="{{ $activities->previousPageUrl() }}"
                            class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase tracking-widest">
                            < Newer</a>
                    @endif

                    @if ($activities->count() > 0)
                        <form action="{{ route('activities.index') }}" method="GET" class="relative">
                            <div onclick="document.getElementById('jump_to_date_activity').showPicker()"
                                class="px-6 py-2 bg-gray-800 border border-gray-700 rounded-2xl text-orange-400 font-bold text-sm shadow-xl hover:border-orange-500 hover:bg-gray-750 transition-all cursor-pointer group flex items-center">
                                <i class="far fa-calendar-alt mr-2 group-hover:scale-110 transition-transform"></i>
                                {{ \Carbon\Carbon::parse($activities->first()->date_start)->format('d M') }} —
                                {{ \Carbon\Carbon::parse($activities->last()->date_start)->format('d M Y') }}
                                <input type="date" id="jump_to_date_activity" name="filter_date"
                                    class="absolute inset-0 opacity-0 cursor-pointer" onchange="this.form.submit()">
                            </div>
                        </form>
                    @endif

                    @if ($activities->hasMorePages())
                        <a href="{{ $activities->nextPageUrl() }}"
                            class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase tracking-widest">Older
                            ></a>
                    @else
                        <span
                            class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 text-xs font-bold uppercase">Oldest</span>
                    @endif
                </div>

                {{-- ... kode sebelumnya (Header, Stats, Pagination) tetap sama ... --}}

                <div class="lg:col-span-2 space-y-6">
                    {{-- ... Pagination ... --}}

                    @forelse($groupedActivities as $date => $dailyActivities)
                        @php
                            $dailyTransport = $dailyActivities->sum('cost_to_there');
                            $dailyExpenses = $dailyActivities->flatMap->expenses->sum('amount');
                            $dailyTotal = $dailyTransport + $dailyExpenses;
                        @endphp

                        <div class="bg-gray-800 rounded-3xl border border-gray-700 shadow-xl overflow-hidden mb-6">
                            <div class="bg-gray-750/50 p-5 border-b border-gray-700 flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="bg-orange-500/20 text-orange-400 border border-orange-500/30 text-xs font-bold px-3 py-1 rounded-xl uppercase tracking-wider">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                    </div>
                                    <h3 class="text-white font-bold text-lg">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Total
                                        Harian</p>
                                    <span class="text-orange-400 font-black text-lg">Rp
                                        {{ number_format($dailyTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="divide-y divide-gray-700/50">
                                @foreach ($dailyActivities as $activity)
                                    @php
                                        $expenseTotal = $activity->expenses->sum('amount');
                                        $grandTotal = $activity->cost_to_there + $expenseTotal;
                                        $hasDetails = $activity->cost_to_there > 0 || $activity->expenses->count() > 0;
                                    @endphp

                                    {{-- WRAPPER KARTU DENGAN ALPINE JS --}}
                                    <div x-data="{ expanded: false }" class="group transition-colors hover:bg-gray-700/30">

                                        <div class="p-5 flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="h-12 w-12 rounded-2xl bg-gray-900/50 flex items-center justify-center text-orange-400 border border-gray-700 group-hover:border-orange-500/50 transition-all">
                                                    <i class="fas fa-map-marker-alt text-lg"></i>
                                                </div>

                                                <div>
                                                    <h4
                                                        class="text-white font-semibold group-hover:text-orange-400 transition-colors">
                                                        {{ $activity->title }}</h4>
                                                    <div class="flex items-center space-x-2 mt-1">
                                                        <span
                                                            class="text-[10px] font-bold text-gray-500 uppercase bg-gray-900 px-2 py-0.5 rounded border border-gray-700">
                                                            {{ $activity->transportation }}
                                                        </span>
                                                        <span
                                                            class="text-[10px] font-bold text-gray-500 uppercase bg-gray-900 px-2 py-0.5 rounded border border-gray-700">
                                                            {{ $activity->distance_in_km }} KM dari tempat asal
                                                        </span>
                                                        <span class="text-gray-600 text-xs">•</span>
                                                        <p class="text-gray-400 text-xs">
                                                            {{ $activity->activity_location ?? 'Lokasi tidak diset' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-6">
                                                <div class="text-right">
                                                    <p class="text-white font-bold text-lg">Rp
                                                        {{ number_format($grandTotal, 0, ',', '.') }}</p>

                                                    @if ($hasDetails)
                                                        <button @click="expanded = ! expanded"
                                                            class="text-[11px] font-medium text-blue-400 hover:text-blue-300 flex items-center justify-end w-full space-x-1 mt-1 focus:outline-none">
                                                            <span
                                                                x-text="expanded ? 'Tutup Rincian' : 'Lihat Rincian'">Lihat
                                                                Rincian</span>
                                                            <i class="fas fa-chevron-down transition-transform duration-300"
                                                                :class="{ 'rotate-180': expanded }"></i>
                                                        </button>
                                                    @else
                                                        <p class="text-[10px] text-gray-600 italic mt-1">Tanpa biaya</p>
                                                    @endif
                                                </div>

                                                <div
                                                    class="flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity border-l border-gray-700 pl-4">
                                                    <a href="{{ route('activities.edit', $activity) }}"
                                                        class="text-gray-500 hover:text-blue-400 transition">
                                                        <i class="fas fa-pen text-sm"></i>
                                                    </a> 
                                                    <form action="{{ route('activities.destroy', $activity)}}"
                                                        method="POST"
                                                        onsubmit="return confirm('Hapus aktivitas ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="text-gray-500 hover:text-red-400 transition">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="expanded" x-collapse
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            class="bg-gray-900/40 border-t border-gray-700/50 px-5 py-4 mx-2 mb-2 rounded-xl">

                                            <p
                                                class="text-[10px] uppercase font-bold text-gray-500 mb-3 tracking-wider">
                                                Rincian Pengeluaran</p>

                                            <div class="space-y-3 text-sm">
                                                @if ($activity->cost_to_there > 0)
                                                    <div class="flex justify-between items-center text-gray-300">
                                                        <div class="flex items-center space-x-3">
                                                            <div
                                                                class="w-6 h-6 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-400 text-xs">
                                                                <i class="fas fa-gas-pump"></i>
                                                            </div>
                                                            <span>Biaya Transportasi
                                                                ({{ ucfirst($activity->transportation) }})</span>
                                                        </div>
                                                        <span class="font-mono">Rp
                                                            {{ number_format($activity->cost_to_there, 0, ',', '.') }}</span>
                                                    </div>
                                                @endif

                                                @foreach ($activity->expenses as $expense)
                                                    <div
                                                        class="flex justify-between items-center text-gray-300 border-t border-gray-700/50 pt-2 mt-2">
                                                        <div class="flex items-center space-x-3">
                                                            <div
                                                                class="w-6 h-6 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-400 text-xs">
                                                                <i class="fas fa-receipt"></i>
                                                            </div>
                                                            <div class="flex flex-col">
                                                                <span>{{ $expense->category }}</span>
                                                                @if ($expense->description)
                                                                    <span
                                                                        class="text-[10px] text-gray-500">{{ Str::limit($expense->description, 30) }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <span class="font-mono">Rp
                                                            {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                    {{-- END WRAPPER --}}
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div
                            class="text-center py-16 bg-gray-800 rounded-3xl border-2 border-dashed border-gray-700 text-gray-500">
                            <i class="far fa-folder-open text-4xl mb-3 opacity-50"></i>
                            <p>Belum ada aktivitas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
