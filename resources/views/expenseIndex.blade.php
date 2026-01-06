<x-app-layout>
    <x-slot name="header-title">Daftar Pengeluaran</x-slot>
    <x-slot name="header-subtitle">Kelola semua transaksi keluar Anda</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-white">Riwayat Pengeluaran</h2>
                    <p class="text-sm text-gray-400">Dikelompokkan berdasarkan tanggal transaksi</p>
                </div>
                <a href="{{ route('expenses.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-[#ff6b00] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#ff8c42] transition shadow-lg shadow-orange-500/30">
                    <i class="fas fa-plus mr-2"></i> Tambah
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-wallet text-5xl text-orange-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Total Bulan Ini</div>
                    <div class="text-2xl font-bold text-white mt-1">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-chart-line text-5xl text-blue-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Rata-rata Harian</div>
                    <div class="text-2xl font-bold text-white mt-1">
                        Rp {{ number_format($averageDaily, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="fas fa-fire text-5xl text-red-500"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Kategori Terboros</div>
                    <div class="text-xl font-bold text-white mt-1 truncate">
                        {{ $categories->first()->category ?? '-' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $categories->first() ? 'Rp ' . number_format($categories->first()->total, 0, ',', '.') : 'Aman' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- <div class="lg:col-span-2 space-y-8">
                    @php
                        // PERBAIKAN LOGIC GROUPING (ANTI ERROR)
                        $groupedExpenses = $expenses->getCollection()->groupBy(function($item) {
                            // 1. Prioritaskan kolom 'date', jika kosong pakai 'created_at'
                            $tanggal = $item->date ?? $item->created_at;
                            
                            // 2. Paksa parsing menggunakan Carbon::parse()
                            // Ini menjamin code berjalan baik data berupa String maupun Object
                            return \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
                        });
                    @endphp

                    @forelse($groupedExpenses as $date => $dailyExpenses)
                        <div class="animate-fade-in-up">
                            
                            <div class="flex items-center justify-between mb-4 px-1 sticky top-0 bg-gray-900/90 backdrop-blur-sm py-2 z-10">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-orange-600/20 text-orange-400 border border-orange-600/30 text-xs font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                    </div>
                                    <h3 class="text-white font-semibold text-lg">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                    </h3>
                                </div>
                                <span class="text-gray-400 text-sm font-medium">
                                    Total: <span class="text-orange-400 font-bold ml-1">Rp {{ number_format($dailyExpenses->sum('amount'), 0, ',', '.') }}</span>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($dailyExpenses as $expense)
                                    <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg hover:border-orange-500/50 hover:shadow-orange-500/10 transition-all duration-300 group flex flex-col justify-between h-full relative overflow-hidden">
                                        
                                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl group-hover:bg-orange-500/20 transition-all"></div>

                                        <div class="flex items-start space-x-4 relative z-10">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gray-700/50 flex items-center justify-center text-orange-400 border border-gray-600 group-hover:scale-110 transition-transform">
                                                <i class="fas fa-receipt"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex justify-between items-start">
                                                    <h4 class="text-white font-bold text-base leading-tight truncate pr-2">
                                                        {{ $expense->activity ? $expense->activity->title : ($expense->category ?? 'Pengeluaran') }}
                                                    </h4>
                                                </div>
                                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-700 text-gray-300 border border-gray-600">
                                                    {{ $expense->category ?? 'Umum' }}
                                                </span>
                                                @if($expense->description)
                                                    <p class="text-gray-400 text-xs mt-2 line-clamp-2">
                                                        {{ $expense->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-5 pt-4 border-t border-gray-700/50 flex items-center justify-between relative z-10">
                                            <span class="text-xl font-bold text-orange-400">
                                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                            </span>

                                            <div class="flex items-center space-x-1 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('expenses.edit', $expense->id) }}" 
                                                   class="h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-blue-600/80 transition-all">
                                                    <i class="fas fa-pen text-xs"></i>
                                                </a>
                                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-red-600/80 transition-all">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-gray-800 rounded-3xl border-2 border-dashed border-gray-700">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-700/50 mb-4 text-gray-500">
                                <i class="fas fa-box-open text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Belum ada pengeluaran</h3>
                            <p class="text-gray-400 text-sm">Uang Anda masih utuh (atau belum dicatat).</p>
                        </div>
                    @endforelse

                    <div class="mt-6">
                        {{ $expenses->links() }}
                    </div>
                </div> --}}
                <div class="lg:col-span-2 space-y-6">
                    @php
                        // Group the paginated items by their date
                        $groupedExpenses = $expenses->getCollection()->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
                        });
                    @endphp
                    <div class="mt-10 flex flex-col items-center space-y-4">
                        <div class="flex items-center space-x-4">
                            @if ($expenses->onFirstPage())
                                <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 cursor-not-allowed text-xs font-bold uppercase tracking-widest">
                                    Newest
                                </span>
                            @else
                                <a href="{{ $expenses->previousPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase tracking-widest">
                                    < Newer
                                </a>
                            @endif
                            
                            @if ($expenses->count() > 0)
                                <form action="{{ route('expenses.index') }}" method="GET" class="relative">
                                    <div onclick="document.getElementById('jump_to_date').showPicker()" 
                                        class="px-6 py-2 bg-gray-800 border border-gray-700 rounded-2xl text-orange-400 font-bold text-sm shadow-xl hover:border-orange-500 hover:bg-gray-750 transition-all cursor-pointer group flex items-center">
                                        
                                        <i class="far fa-calendar-alt mr-2 group-hover:scale-110 transition-transform"></i>
                                        
                                        {{ \Carbon\Carbon::parse($expenses->first()->date)->format('d M') }} 
                                        — 
                                        {{ \Carbon\Carbon::parse($expenses->last()->date)->format('d M Y') }}

                                        <input type="date" 
                                            id="jump_to_date" 
                                            name="filter_date" 
                                            class="absolute inset-0 opacity-0 cursor-pointer" 
                                            onchange="this.form.submit()">
                                    </div>
                                </form>
                            @endif

                            @if ($expenses->hasMorePages())
                                <a href="{{ $expenses->nextPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase tracking-widest">
                                    Older >
                                </a>
                            @else
                                <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 cursor-not-allowed text-xs font-bold uppercase tracking-widest">
                                    Oldeste
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="mt-10 flex flex-col items-center space-y-4">
                        

                        <div class="flex items-center space-x-4">
                            @if ($expenses->onFirstPage())
                                <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 cursor-not-allowed text-xs font-bold uppercase">Newest</span>
                            @else
                                <a href="{{ $expenses->previousPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase">< Newer</a>
                            @endif
                            
                            @if ($expenses->count() > 0)
                                <div class="px-6 py-2 bg-gray-800 border border-gray-700 rounded-2xl text-orange-400 font-bold text-sm shadow-xl">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    {{ \Carbon\Carbon::parse($expenses->first()->date)->format('d M') }} 
                                    — 
                                    {{ \Carbon\Carbon::parse($expenses->last()->date)->format('d M Y') }}
                                </div>
                            @endif

                            @if ($expenses->hasMorePages())
                                <a href="{{ $expenses->nextPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase">Older ></a>
                            @else
                                <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 cursor-not-allowed text-xs font-bold uppercase">Oldest</span>
                            @endif
                        </div>
                    </div> --}}

                    @forelse($groupedExpenses as $date => $dailyItems)
                        <div class="bg-gray-800 rounded-3xl border border-gray-700 shadow-xl overflow-hidden animate-fade-in-up">
                            <div class="bg-gray-750/50 p-5 border-b border-gray-700 flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-orange-500/20 text-orange-400 border border-orange-500/30 text-xs font-bold px-3 py-1 rounded-xl uppercase tracking-wider">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                    </div>
                                    <h3 class="text-white font-bold text-lg">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                    </h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Total Hari Ini</p>
                                    <span class="text-orange-400 font-black text-lg">
                                        Rp {{ number_format($dailyItems->sum('amount'), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="divide-y divide-gray-700/50">
                                @foreach($dailyItems as $expense)
                                    <div class="p-5 hover:bg-gray-700/30 transition-colors group flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-12 w-12 rounded-2xl bg-gray-900/50 flex items-center justify-center text-orange-400 border border-gray-700 group-hover:border-orange-500/50 transition-all">
                                                <i class="fas fa-receipt text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white font-semibold group-hover:text-orange-400 transition-colors">
                                                    {{ $expense->activity ? $expense->activity->title : ($expense->category ?? 'Pengeluaran') }}
                                                </h4>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $expense->category }}</span>
                                                    @if($expense->description)
                                                        <span class="text-gray-600 text-xs">•</span>
                                                        <p class="text-gray-400 text-xs truncate max-w-[150px] md:max-w-xs">{{ $expense->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-4">
                                            <span class="text-white font-bold text-lg">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                            <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('expenses.edit', $expense->id) }}" class="p-2 text-gray-400 hover:text-blue-400"><i class="fas fa-pen text-sm"></i></a>
                                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
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
                        <div class="text-center py-16 bg-gray-800 rounded-3xl border-2 border-dashed border-gray-700">
                            <p class="text-gray-500">Belum ada data pengeluaran.</p>
                        </div>
                    @endforelse

                    {{-- <div class="mt-10 flex flex-col items-center space-y-4">
                        @if ($expenses->count() > 0)
                            <div class="px-6 py-2 bg-gray-800 border border-gray-700 rounded-2xl text-orange-400 font-bold text-sm shadow-xl">
                                <i class="far fa-calendar-alt mr-2"></i>
                                {{ \Carbon\Carbon::parse($expenses->first()->date)->format('d M') }} 
                                — 
                                {{ \Carbon\Carbon::parse($expenses->last()->date)->format('d M Y') }}
                            </div>
                        @endif

                        <div class="flex items-center space-x-4">
                            @if ($expenses->onFirstPage())
                                <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 cursor-not-allowed text-xs font-bold uppercase">Newer</span>
                            @else
                                <a href="{{ $expenses->previousPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase">< Newer</a>
                            @endif

                            @if ($expenses->hasMorePages())
                                <a href="{{ $expenses->nextPageUrl() }}" class="px-6 py-3 bg-gray-800 text-white rounded-2xl border border-gray-700 hover:border-orange-500 transition-all text-xs font-bold uppercase">Older ></a>
                            @else
                                <span class="px-6 py-3 bg-gray-800/50 text-gray-600 rounded-2xl border border-gray-700 cursor-not-allowed text-xs font-bold uppercase">Older</span>
                            @endif
                        </div>
                    </div> --}}
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-lg sticky top-6">
                        <div class="p-6 border-b border-gray-700">
                            <h3 class="text-lg font-bold text-white">Rincian Kategori</h3>
                            <p class="text-xs text-gray-400 mt-1">Ke mana uang Anda pergi?</p>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach ($categories as $cat)
                                <div class="group">
                                    <div class="flex justify-between items-end text-sm mb-2">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-200">{{ $cat->category }}</span>
                                            <span class="text-[10px] text-gray-500">{{ $cat->count }} Transaksi</span>
                                        </div>
                                        <span class="text-white font-bold">Rp {{ number_format($cat->total, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="w-full bg-gray-700 rounded-full h-2 overflow-hidden">
                                        @php
                                            $percent = $totalExpense > 0 ? ($cat->total / $totalExpense) * 100 : 0;
                                        @endphp
                                        <div class="bg-gradient-to-r from-orange-600 to-orange-400 h-2 rounded-full shadow-[0_0_10px_rgba(255,107,0,0.5)] transition-all duration-1000 ease-out"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if ($categories->isEmpty())
                                <div class="text-center text-gray-500 py-4 text-sm">
                                    Belum ada data.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>