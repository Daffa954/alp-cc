<x-app-layout>
    <x-slot name="header-title">Pemasukan</x-slot>
    <x-slot name="header-subtitle">Kelola sumber pendapatan Anda</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-white">Riwayat Pemasukan</h2>
                <a href="{{ route('incomes.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition shadow-lg shadow-green-500/30">
                    <i class="fas fa-plus mr-2"></i> Tambah Pemasukan
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Sumber</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Catatan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($incomes as $income)
                                <tr class="hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $income->date_received ? $income->date_received->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                        {{ $income->source ?? 'Tanpa Sumber' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-400 max-w-xs truncate">
                                        {{ $income->notes ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-400">
                                        + Rp {{ number_format($income->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center space-x-3">
                                            <a href="{{ route('incomes.edit', $income->id) }}" class="text-blue-400 hover:text-blue-300">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('incomes.destroy', $income->id) }}" method="POST" onsubmit="return confirm('Hapus data pemasukan ini?');">
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
                                        Belum ada data pemasukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-700">
                    {{ $incomes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>