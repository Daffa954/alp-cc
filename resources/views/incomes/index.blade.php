<x-app-layout>
    <x-slot name="header-title">Daftar Pemasukan</x-slot>
    <x-slot name="header-subtitle">Kelola semua aliran dana masuk Anda</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-white">Riwayat Pemasukan</h2>
                    <p class="text-sm text-gray-400">Dikelompokkan berdasarkan tanggal transaksi</p>
                </div>
                <a href="{{ route('incomes.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition shadow-lg shadow-green-500/30">
                    <i class="fas fa-plus mr-2"></i> Tambah
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-coins text-5xl text-green-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Total Bulan Ini</div>
                    <div class="text-2xl font-bold text-white mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-chart-line text-5xl text-blue-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Rata-rata Harian</div>
                    <div class="text-2xl font-bold text-white mt-1">Rp {{ number_format($averageDaily, 0, ',', '.') }}</div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-bullseye text-5xl text-green-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Sumber Terbesar</div>
                    <div class="text-xl font-bold text-white mt-1 truncate">{{ $sources->first()->source ?? '-' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    @php
                        $groupedIncomes = $incomes->getCollection()->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->date_received)->format('Y-m-d');
                        });
                    @endphp

                    <div class="mt-4 mb-6 flex items-center justify-center space-x-4">
                        @if ($incomes->onFirstPage())
                            <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 text-xs font-bold uppercase">Newest</span>
                        @else
                            <a href="{{ $incomes->previousPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-green-500 transition-all text-xs font-bold uppercase">< Newer</a>
                        @endif

                        @if ($incomes->count() > 0)
                            <form action="{{ route('incomes.index') }}" method="GET" class="relative">
                                <div onclick="document.getElementById('jump_to_date_income').showPicker()" 
                                     class="px-6 py-2 bg-gray-800 border border-gray-700 rounded-2xl text-green-400 font-bold text-sm shadow-xl hover:border-green-500 hover:bg-gray-750 transition-all cursor-pointer group flex items-center">
                                    <i class="far fa-calendar-alt mr-2 group-hover:scale-110 transition-transform"></i>
                                    {{ \Carbon\Carbon::parse($incomes->first()->date_received)->format('d M') }} — {{ \Carbon\Carbon::parse($incomes->last()->date_received)->format('d M Y') }}
                                    <input type="date" id="jump_to_date_income" name="filter_date" class="absolute inset-0 opacity-0 cursor-pointer" onchange="this.form.submit()">
                                </div>
                            </form>
                        @endif

                        @if ($incomes->hasMorePages())
                            <a href="{{ $incomes->nextPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-green-500 transition-all text-xs font-bold uppercase">Older ></a>
                        @else
                            <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 text-xs font-bold uppercase">Oldest</span>
                        @endif
                    </div>

                    @forelse($groupedIncomes as $date => $dailyIncomes)
                        <div class="bg-gray-800 rounded-3xl border border-gray-700 shadow-xl overflow-hidden">
                            <div class="bg-gray-750/50 p-5 border-b border-gray-700 flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-green-500/20 text-green-400 border border-green-500/30 text-xs font-bold px-3 py-1 rounded-xl uppercase tracking-wider">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                    </div>
                                    <h3 class="text-white font-bold text-lg">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Pemasukan Hari Ini</p>
                                    <span class="text-green-400 font-black text-lg">Rp {{ number_format($dailyIncomes->sum('amount'), 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="divide-y divide-gray-700/50">
                                @foreach($dailyIncomes as $income)
                                    <div class="p-5 hover:bg-gray-700/30 transition-colors group flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-12 w-12 rounded-2xl bg-gray-900/50 flex items-center justify-center text-green-400 border border-gray-700 group-hover:border-green-500/50 transition-all">
                                                <i class="fas fa-wallet text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white font-semibold group-hover:text-green-400 transition-colors">{{ $income->source }}</h4>
                                                @if($income->notes) <p class="text-gray-400 text-xs mt-1 truncate max-w-xs">{{ $income->notes }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="text-white font-bold text-lg">Rp {{ number_format($income->amount, 0, ',', '.') }}</span>
                                            <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('incomes.edit', ['income' => $income]) }}" class="p-2 text-gray-400 hover:text-blue-400"><i class="fas fa-pen text-sm"></i></a>
                                                <form action="{{ route('incomes.destroy', $income) }}" method="POST">
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
                        <div class="text-center py-16 bg-gray-800 rounded-3xl border-2 border-dashed border-gray-700 text-gray-500">Belum ada pemasukan.</div>
                    @endforelse
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-lg sticky top-6">
                        <div class="p-6 border-b border-gray-700">
                            <h3 class="text-lg font-bold text-white">Ringkasan Sumber</h3>
                            <p class="text-xs text-gray-400 mt-1">Dari mana dana Anda berasal?</p>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach ($sources as $source)
                                <div class="group">
                                    <div class="flex justify-between items-end text-sm mb-2">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-200">{{ $source->source }}</span>
                                            <span class="text-[10px] text-gray-500">{{ $source->count }} Transaksi</span>
                                        </div>
                                        <span class="text-white font-bold">Rp {{ number_format($source->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="w-full bg-gray-700 rounded-full h-2 overflow-hidden">
                                        @php $percent = $totalIncome > 0 ? ($source->total / $totalIncome) * 100 : 0; @endphp
                                        <div class="bg-gradient-to-r from-green-600 to-green-400 h-2 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>