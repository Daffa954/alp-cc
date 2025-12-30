<x-app-layout>
    <x-slot name="header-title">Riwayat Analisis</x-slot>
    <x-slot name="header-subtitle">Arsip lengkap evaluasi keuangan Anda</x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('reports.index') }}"
                class="inline-flex items-center text-gray-400 hover:text-white transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Analisis Baru
            </a>
        </div>

        <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">
            <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg">Daftar Laporan</h3>
                <span class="text-xs text-gray-400">Total: {{ $reports->total() }} Laporan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4">Periode</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Pengeluaran</th>
                            <th class="px-6 py-4 text-right">Sisa Saldo</th>
                            <th class="px-6 py-4 text-center">Dibuat Pada</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 font-medium text-white">
                                    @if ($report->type == 'weekly')
                                        Minggu ke-{{ substr($report->period_key, -2) }}
                                        ({{ substr($report->period_key, 0, 4) }})
                                    @else
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $report->period_key)->format('F Y') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $report->type == 'weekly' ? 'bg-blue-900/30 text-blue-400' : 'bg-purple-900/30 text-purple-400' }}">
                                        {{ ucfirst($report->type == 'weekly' ? 'Mingguan' : 'Bulanan') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($report->status == 'danger')
                                        <span class="text-red-400 flex items-center gap-1"><i
                                                class="fas fa-exclamation-circle"></i> Bahaya</span>
                                    @elseif($report->status == 'warning')
                                        <span class="text-yellow-400 flex items-center gap-1"><i
                                                class="fas fa-exclamation-triangle"></i> Waspada</span>
                                    @else
                                        <span class="text-green-400 flex items-center gap-1"><i
                                                class="fas fa-check-circle"></i> Sehat</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-white">
                                    Rp {{ number_format($report->total_expense, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 text-right {{ $report->balance < 0 ? 'text-red-400' : 'text-green-400' }}">
                                    Rp {{ number_format($report->balance, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $report->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('reports.show', $report->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-700 text-gray-300 hover:bg-[#ff6b00] hover:text-white transition">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-folder-open text-4xl mb-3 block opacity-50"></i>
                                    Belum ada riwayat laporan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-700">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
