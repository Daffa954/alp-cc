<x-app-layout>
    <x-slot name="header-title">Pemasukan</x-slot>
    <x-slot name="header-subtitle">Kelola sumber pendapatan Anda</x-slot>

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

            @if(session('success'))
                <div class="p-4 rounded-xl bg-green-900/50 border border-green-500 text-green-200 mb-6">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

          @php
                $groupedIncomes = $incomes->getCollection()->groupBy(function($item) {
                    return $item->date_received ? $item->date_received->format('Y-m-d') : 'Tanpa Tanggal';
                });
            @endphp

            <div class="space-y-8">
                @forelse($groupedIncomes as $date => $dailyIncomes)
                    <div class="animate-fade-in-up">
                        
                        <div class="flex items-center justify-between mb-4 px-1">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-600/20 text-green-400 border border-green-600/30 text-xs font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l') }}
                                </div>
                                <h3 class="text-white font-semibold text-lg">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                </h3>
                            </div>
                            <span class="text-gray-400 text-sm font-medium">
                                Total: <span class="text-green-400 font-bold ml-1">Rp {{ number_format($dailyIncomes->sum('amount'), 0, ',', '.') }}</span>
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($dailyIncomes as $income)
                                <div class="bg-gray-800 rounded-2xl p-5 border border-gray-700 shadow-lg hover:border-green-500/50 hover:shadow-green-500/10 transition-all duration-300 group flex flex-col justify-between h-full relative overflow-hidden">
                                    
                                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-500/10 rounded-full blur-2xl group-hover:bg-green-500/20 transition-all"></div>

                                    <div class="flex items-start justify-between relative z-10">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-xl bg-gray-700/50 flex items-center justify-center text-green-400 border border-gray-600 group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-wallet text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-white font-bold text-lg leading-tight line-clamp-1" title="{{ $income->source }}">
                                                    {{ $income->source ?? 'Tanpa Sumber' }}
                                                </h4>
                                                @if($income->notes)
                                                    <p class="text-gray-400 text-xs mt-1 line-clamp-2 leading-relaxed">
                                                        {{ $income->notes }}
                                                    </p>
                                                @else
                                                    <p class="text-gray-600 text-xs mt-1 italic">Tidak ada catatan</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t border-gray-700/50 flex items-center justify-between relative z-10">
                                        <span class="text-2xl font-bold text-green-400 tracking-tight">
                                            <span class="text-sm font-normal text-gray-500 mr-1">Rp</span>{{ number_format($income->amount, 0, ',', '.') }}
                                        </span>

                                        <div class="flex items-center space-x-1">
                                            <a href="{{ route('incomes.edit', $income->id) }}" 
                                               class="h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-blue-600/80 transition-all" 
                                               title="Edit">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('incomes.destroy', $income->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="h-8 w-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-red-600/80 transition-all" 
                                                        title="Hapus">
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
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-700/50 mb-4 text-gray-500 animate-bounce">
                            <i class="fas fa-coins text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Belum ada data</h3>
                        <p class="text-gray-400 max-w-sm mx-auto mb-6">Mulai catat aliran dana masuk Anda untuk memantau kesehatan finansial.</p>
                        <a href="{{ route('incomes.create') }}" class="px-6 py-3 bg-green-600 hover:bg-green-500 text-white rounded-xl font-semibold shadow-lg shadow-green-900/20 transition">
                            Tambah Pemasukan
                        </a>
                    </div>
                @endforelse
            </div>
            </div>

            <div class="mt-6">
                {{ $incomes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>