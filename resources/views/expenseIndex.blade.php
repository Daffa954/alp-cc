<x-app-layout>
    <x-slot name="header-title">Daftar Pengeluaran</x-slot>
    <x-slot name="header-subtitle">Kelola semua transaksi Anda</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6 pb-12 md:pb-0">

            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Semua Transaksi') }}
                </h2>
                {{-- Tombol Tambah --}}
                <a href="{{ route('expenses.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-[#ff6b00] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#ff8c42] focus:outline-none focus:ring ring-orange-300 transition ease-in-out duration-150 shadow-lg shadow-orange-500/30">
                    <i class="fas fa-plus mr-2"></i> Tambah Pengeluaran
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div
                    class="bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-700 p-6 relative group hover:border-[#ff6b00] transition-colors duration-300">
                    <div class="absolute right-4 top-4 text-gray-600 group-hover:text-[#ff6b00] transition-colors">
                        <i class="fas fa-wallet text-2xl opacity-50"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Total Bulan Ini</div>
                    <div class="text-2xl font-bold text-white mt-1">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </div>
                </div>

                <div
                    class="bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-700 p-6 relative group hover:border-green-500 transition-colors duration-300">
                    <div class="absolute right-4 top-4 text-gray-600 group-hover:text-green-500 transition-colors">
                        <i class="fas fa-chart-line text-2xl opacity-50"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Rata-rata Harian</div>
                    <div class="text-2xl font-bold text-white mt-1">
                        Rp {{ number_format($averageDaily, 0, ',', '.') }}
                    </div>
                </div>

                <div
                    class="bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-700 p-6 relative group hover:border-purple-500 transition-colors duration-300">
                    <div class="absolute right-4 top-4 text-gray-600 group-hover:text-purple-500 transition-colors">
                        <i class="fas fa-exclamation-circle text-2xl opacity-50"></i>
                    </div>
                    <div class="text-gray-400 text-sm font-medium">Kategori Terboros</div>
                    <div class="text-xl font-bold text-white mt-1 truncate">
                        {{ $categories->first()->category ?? '-' }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $categories->first() ? 'Rp ' . number_format($categories->first()->total, 0, ',', '.') : 'Belum ada data' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-700">
                    <div class="p-6 border-b border-gray-700">
                        <h3 class="text-lg font-medium text-white mb-4">Riwayat Transaksi</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-700/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Aktivitas</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Kategori</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Deskripsi</th>

                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                                            Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @forelse($expenses as $expense)
                                        <tr class="hover:bg-gray-700/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                {{ $expense->created_at->format('d M, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                                {{-- Menggunakan 'title' sesuai struktur Activity Anda --}}
                                                {{ $expense->activity ? $expense->activity->title : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-gray-700 text-gray-300 border border-gray-600">
                                                    {{ $expense->category }}
                                                </span>
                                            </td>
                                             <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-gray-700 text-gray-300 border border-gray-600">
                                                    {{ $expense->description }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-white text-right font-bold">
                                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                <i class="fas fa-box-open text-2xl mb-2 opacity-50"></i><br>
                                                Belum ada data pengeluaran.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 text-gray-300">
                            {{ $expenses->links() }}
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-700 h-fit">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-white mb-6">Rincian Kategori</h3>
                        <div class="space-y-5">
                            @foreach ($categories as $cat)
                                <div>
                                    <div class="flex justify-between items-center text-sm mb-2">
                                        <span class="font-medium text-gray-300">{{ $cat->category }}</span>
                                        <span class="text-white font-bold">Rp
                                            {{ number_format($cat->total, 0, ',', '.') }}</span>
                                    </div>
                                    {{-- Progress bar visual --}}
                                    <div class="w-full bg-gray-700 rounded-full h-2">
                                        @php
                                            $percent = $totalExpense > 0 ? ($cat->total / $totalExpense) * 100 : 0;
                                        @endphp
                                        <div class="bg-[#ff6b00] h-2 rounded-full shadow-[0_0_10px_rgba(255,107,0,0.5)]"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 text-right">{{ $cat->count }} Transaksi
                                    </div>
                                </div>
                            @endforeach

                            @if ($categories->isEmpty())
                                <div class="text-center text-gray-500 py-4 text-sm">
                                    Belum ada data kategori.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
