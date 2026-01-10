<x-app-layout>
    <x-slot name="header-title">Laporan & Analisis</x-slot>
    <x-slot name="header-subtitle">Evaluasi keuangan dengan bantuan AI</x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div
                class="mb-6 bg-green-900/50 border border-green-500 text-green-200 px-4 py-3 rounded-xl flex items-center shadow-lg">
                <i class="fas fa-check-circle text-xl mr-3"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="mb-6 bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded-xl flex items-center shadow-lg">
                <i class="fas fa-exclamation-circle text-xl mr-3"></i> {{ session('error') }}
            </div>
        @endif

        {{-- AREA INPUT: KALENDER & KONTROL --}}
        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl mb-8">
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- KIRI: Kalender Interaktif --}}
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-white font-bold text-lg"><i class="far fa-calendar-alt mr-2 text-blue-400"></i>
                            Pilih Periode</h3>
                        <div class="flex items-center space-x-2 bg-gray-900 rounded-lg p-1">
                            <button id="calPrev"
                                class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-md transition"><i
                                    class="fas fa-chevron-left"></i></button>
                            <span id="calMonthYear"
                                class="text-white font-bold text-sm min-w-[120px] text-center select-none"></span>
                            <button id="calNext"
                                class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-md transition"><i
                                    class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-7 gap-1 mb-2 text-center text-xs text-gray-500 font-bold uppercase tracking-wider select-none">
                        <div>Min</div>
                        <div>Sen</div>
                        <div>Sel</div>
                        <div>Rab</div>
                        <div>Kam</div>
                        <div>Jum</div>
                        <div>Sab</div>
                    </div>

                    <div id="interactiveCalendar" class="grid grid-cols-7 gap-1 select-none"></div>

                    {{-- Legend --}}
                    <div class="mt-4 flex flex-wrap gap-4 text-xs text-gray-400">
                        <div class="flex items-center"><span class="w-3 h-3 bg-[#ff6b00] rounded mr-2"></span> Cakupan
                        </div>
                        <div class="flex items-center"><span class="w-3 h-3 border border-blue-500 rounded mr-2"></span>
                            Hari Ini</div>
                        <div class="flex items-center gap-2 border-l border-gray-600 pl-4 ml-2">
                            <span class="flex items-center"><span
                                    class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1"></span> Exp</span>
                            <span class="flex items-center"><span
                                    class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span> Inc</span>
                            <span class="flex items-center"><span
                                    class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-1"></span> Act</span>
                        </div>
                    </div>
                </div>

                {{-- KANAN: Form Controls --}}
                <div class="w-full lg:w-80 flex flex-col border-l border-gray-700 pl-0 lg:pl-8 pt-6 lg:pt-0">
                    <h4 class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-4">Pengaturan</h4>

                    <form id="mainForm" method="POST" action="{{ route('reports.generate') }}"
                        onsubmit="showLoading(this)">
                        @csrf
                        <input type="hidden" name="date" id="inputDate" value="{{ $date }}">

                        {{-- Tipe Laporan --}}
                        <div class="mb-5">
                            <label class="block text-gray-300 text-sm mb-2">Jenis Laporan</label>
                            <div
                                class="bg-gray-900 p-1 rounded-xl flex items-center justify-between border border-gray-600">
                                <label id="lblMonthly"
                                    class="flex-1 text-center cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition text-gray-400 hover:text-white">
                                    <input type="radio" name="type" value="monthly" class="hidden"
                                        {{ $type == 'monthly' ? 'checked' : '' }}> Bulanan
                                </label>
                                <label id="lblWeekly"
                                    class="flex-1 text-center cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition text-gray-400 hover:text-white">
                                    <input type="radio" name="type" value="weekly" class="hidden"
                                        {{ $type == 'weekly' ? 'checked' : '' }}> Mingguan
                                </label>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="mb-5">
                            <label class="block text-gray-300 text-sm mb-2">Tanggal Referensi</label>
                            <div
                                class="px-4 py-3 bg-gray-900 border border-gray-600 rounded-xl text-white font-mono text-sm flex items-center shadow-inner">
                                <i class="fas fa-calendar-day text-orange-500 mr-3"></i>
                                <span
                                    id="displayDateText">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>

                        {{-- AI Model --}}
                        <div class="mb-6 hidden">
                            <label class="block text-gray-300 text-sm mb-2">Model AI</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                    <i class="fas fa-microchip"></i></div>
                                <select name="ai_model"
                                    class="block w-full pl-10 pr-8 py-2.5 bg-gray-900 border border-gray-600 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 appearance-none cursor-pointer">
                                    {{-- <option value="auto" selected>🤖 Auto (Smart)</option>
                                    <option value="gemini">⚡ Gemini (Cepat)</option> --}}
                                    <option value="deepseek">🧠 DeepSeek (Deep)</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i></div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 mt-auto">
                            <button type="submit" id="btn-generate"
                                class="w-full group flex justify-center items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-[0_0_20px_rgba(59,130,246,0.5)] transition transform hover:-translate-y-0.5">
                                <span id="btn-text" class="flex items-center"><i
                                        class="fas fa-robot mr-2 animate-bounce-slow"></i>
                                    {{ isset($report) ? 'Analisis Ulang' : 'Mulai Analisis' }}</span>
                                <span id="btn-loader" class="hidden items-center"><svg
                                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg> Memproses...</span>
                            </button>

                            <button type="button" onclick="goToHistory()"
                                class="w-full flex justify-center items-center px-6 py-3 bg-gray-700 text-gray-300 font-bold rounded-xl hover:bg-gray-600 hover:text-white transition">
                                <i class="fas fa-search mr-2"></i> Lihat Semua Arsip
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODE ARSIP --}}
        @if (isset($is_detail_view) && $is_detail_view)
            <div
                class="mb-6 bg-blue-900/30 border border-blue-500/50 text-blue-200 px-4 py-3 rounded-xl flex items-center justify-between shadow-lg">
                <div class="flex items-center"><i class="fas fa-history text-xl mr-3"></i> <span
                        class="text-sm font-bold">Mode Arsip: {{ $report->created_at->format('d F Y') }}</span></div>
                <a href="{{ route('reports.index') }}"
                    class="text-sm bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg text-white transition">Kembali</a>
            </div>
        @endif

        {{-- HASIL LAPORAN --}}
        @if (isset($report))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-gray-800 rounded-2xl p-6 border-l-8 {{ $report->status == 'danger' ? 'border-red-500' : ($report->status == 'warning' ? 'border-yellow-500' : 'border-green-500') }} shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Kesimpulan
                                    AI</span>
                                <h2 class="text-2xl font-bold text-white mt-1">
                                    {{ $report->status == 'safe' ? '✅ Keuangan Sehat' : ($report->status == 'warning' ? '⚠️ Waspada' : '🚨 Bahaya') }}
                                </h2>
                            </div>
                            <div class="text-right">
                                <span class="text-gray-400 text-xs">Total Pengeluaran</span>
                                <div class="text-xl font-bold text-white">Rp
                                    {{ number_format($report->total_expense, 0, ',', '.') }}</div>
                                @if ($report->percentage_change != 0)
                                    <span
                                        class="text-xs {{ $report->percentage_change > 0 ? 'text-red-400' : 'text-green-400' }}">
                                        {{ $report->percentage_change > 0 ? '▲ Naik' : '▼ Turun' }}
                                        {{ abs($report->percentage_change) }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                        <h3 class="text-lg font-bold text-white mb-4"><i
                                class="fas fa-file-alt text-blue-400 mr-2"></i> Detail Analisis</h3>
                        <p
                            class="text-gray-300 text-sm leading-relaxed whitespace-pre-line border-l-4 border-gray-600 pl-4">
                            {{ $report->ai_analysis }}
                        </p>
                    </div>

                    {{-- BAGIAN REKOMENDASI (YANG DIPERBAIKI) --}}
                    {{-- ... kode sebelumnya ... --}}

                    <div class="mt-6 bg-gray-900/30 rounded-xl p-5 border border-gray-700">
                        <h4 class="text-white font-bold mb-3 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i> Saran Aksi:
                        </h4>

                        <ul class="space-y-2">
                            @php
                                // 1. Pecah string berdasarkan baris baru (\n) atau bullet point (-)
                                // Regex ini menangkap baris baru yang mungkin diawali tanda strip
                                $recommendations = preg_split(
                                    '/\n-?/',
                                    $report->ai_recommendation,
                                    -1,
                                    PREG_SPLIT_NO_EMPTY,
                                );
                            @endphp

                            @forelse($recommendations as $rec)
                                @php $cleanRec = trim($rec); @endphp

                                @if (!empty($cleanRec))
                                    <li class="flex items-start text-gray-300 text-sm">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i>
                                        <span>{{ $cleanRec }}</span>
                                    </li>
                                @endif
                            @empty
                                <li class="text-gray-500 italic">Tidak ada rekomendasi khusus.</li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- ... kode selanjutnya ... --}}

                </div>

                {{-- Kolom Kanan: Peta Boros --}}
                <div class="lg:col-span-1">
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 lg:sticky lg:top-6">
                        <h3 class="text-white font-bold text-lg mb-4">Peta Pengeluaran</h3>
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-300">
                            <span class="font-bold">{{ \Carbon\Carbon::parse($date)->format('F Y') }}</span>
                            <div class="flex gap-2">
                                <span class="text-[10px] text-gray-400 flex items-center"><span
                                        class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>Boros</span>
                                <span class="text-[10px] text-gray-400 flex items-center"><span
                                        class="w-2 h-2 bg-emerald-500 rounded-full mr-1"></span>Ada</span>
                            </div>
                        </div>

                        <div id="calendarGridResult" class="grid grid-cols-7 gap-1 text-center text-xs text-gray-500">
                        </div>

                        @if (!empty($report->wasteful_dates))
                            <div class="mt-4 p-3 bg-red-900/20 border border-red-500/30 rounded-xl text-center">
                                <p class="text-xs text-red-300"><i class="fas fa-exclamation-circle mr-1"></i>
                                    Terdeteksi {{ count($report->wasteful_dates) }} hari boros.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-20 bg-gray-800/50 rounded-3xl border-2 border-dashed border-gray-700">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-700 mb-4"><i
                        class="far fa-calendar-check text-3xl text-gray-400"></i></div>
                <h3 class="text-xl font-bold text-white">Pilih Periode Analisis</h3>
                <p class="text-gray-400 mt-2 text-sm">Klik tanggal di kalender kiri untuk menentukan periode laporan.
                </p>
            </div>
        @endif
    </div>

    @push('scripts')
        {{-- Load file JS eksternal --}}
        @vite('resources/js/page-reports.js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                ReportPage.init({
                    type: "{{ $type }}",
                    date: "{{ $date }}",
                    // Pass data lengkap ke JS
                    dates: {!! json_encode($dates ?? ['expenses' => [], 'incomes' => [], 'activities' => []]) !!},
                    wastefulDates: {!! json_encode($report->wasteful_dates ?? []) !!},
                    routes: {
                        index: "{{ route('reports.index') }}",
                        history: "{{ route('reports.history') }}"
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
