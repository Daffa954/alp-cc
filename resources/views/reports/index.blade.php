<x-app-layout>
    <x-slot name="header-title">Laporan & Analisis</x-slot>
    <x-slot name="header-subtitle">Evaluasi keuangan dengan bantuan AI</x-slot>

    {{-- Tambahkan px-4 agar tidak mepet di HP --}}
    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- 1. ALERT NOTIFIKASI --}}
        @if (session('success'))
            <div
                class="mb-6 bg-green-900/50 border border-green-500 text-green-200 px-4 py-3 rounded-xl flex items-center shadow-lg animate-fade-in-down">
                <i class="fas fa-check-circle text-xl mr-3 flex-shrink-0"></i>
                <span class="text-sm sm:text-base">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded-xl flex items-center shadow-lg animate-fade-in-down">
                <i class="fas fa-exclamation-circle text-xl mr-3 flex-shrink-0"></i>
                <span class="text-sm sm:text-base">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded-xl shadow-lg">
                <ul class="list-disc ml-5 text-sm sm:text-base">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 2. CONTROLS (FILTER & TOMBOL GENERATE) --}}
        <div class="bg-gray-800 p-4 sm:p-6 rounded-2xl border border-gray-700 shadow-xl mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-4">

                {{-- Form Filter Tipe & Tanggal --}}
                <form action="{{ route('reports.index') }}" method="GET" class="w-full lg:w-auto" id="filterForm">
                    <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

                        {{-- Toggle Bulanan/Mingguan --}}
                        <div
                            class="bg-gray-900 p-1 rounded-xl flex items-center justify-between border border-gray-600">
                            <label
                                class="flex-1 text-center cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition {{ $type == 'monthly' ? 'bg-[#ff6b00] text-white shadow-lg' : 'text-gray-400 hover:text-white' }}">
                                <input type="radio" name="type" value="monthly" class="hidden"
                                    onchange="this.form.submit()" {{ $type == 'monthly' ? 'checked' : '' }}>
                                Bulanan
                            </label>
                            <label
                                class="flex-1 text-center cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition {{ $type == 'weekly' ? 'bg-[#ff6b00] text-white shadow-lg' : 'text-gray-400 hover:text-white' }}">
                                <input type="radio" name="type" value="weekly" class="hidden"
                                    onchange="this.form.submit()" {{ $type == 'weekly' ? 'checked' : '' }}>
                                Mingguan
                            </label>
                        </div>

                        {{-- Date Picker --}}
                        <div class="relative w-full sm:w-auto">
                            <input type="date" name="date" value="{{ $date }}"
                                onchange="this.form.submit()"
                                class="w-full sm:w-auto bg-gray-900 border border-gray-600 text-white rounded-xl px-4 py-2 text-sm focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none"
                                style="color-scheme: dark;">
                        </div>
                    </div>
                </form>

                {{-- Tombol Generate AI --}}
                <form action="{{ route('reports.generate') }}" method="POST" onsubmit="showLoading(this)"
                    class="w-full lg:w-auto">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <button type="submit" id="btn-generate"
                        class="w-full lg:w-auto group flex justify-center items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-[0_0_20px_rgba(59,130,246,0.5)] transition transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="btn-text" class="flex items-center">
                            <i class="fas fa-robot mr-2 animate-bounce-slow"></i>
                            {{ isset($report) ? 'Analisis Ulang' : 'Mulai Analisis AI' }}
                        </span>

                        {{-- Spinner Loading (Hidden by default) --}}
                        <span id="btn-loader" class="hidden items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Sedang...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        {{-- 3. INDIKATOR MODE ARSIP --}}
        @if (isset($is_detail_view) && $is_detail_view)
            <div
                class="mb-6 bg-blue-900/30 border border-blue-500/50 text-blue-200 px-4 py-3 rounded-xl flex flex-col sm:flex-row items-center justify-between shadow-lg gap-4 animate-fade-in-down">
                <div class="flex items-center">
                    <i class="fas fa-history text-xl mr-3 flex-shrink-0"></i>
                    <div class="text-sm">
                        <span class="font-bold">Mode Arsip:</span> Laporan lampau
                        ({{ $report->created_at->format('d F Y') }}).
                    </div>
                </div>
                <a href="{{ route('reports.index') }}"
                    class="w-full sm:w-auto text-center text-sm bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg transition text-white">
                    Kembali
                </a>
            </div>
        @endif

        {{-- 4. KONTEN UTAMA LAPORAN --}}
        @if (isset($report))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up">

                {{-- Kolom Kiri: Status, Analisis, Rekomendasi --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Card Status Kesehatan --}}
                    <div
                        class="bg-gray-800 rounded-2xl p-6 border-l-8 {{ $report->status == 'danger' ? 'border-red-500' : ($report->status == 'warning' ? 'border-yellow-500' : 'border-green-500') }} shadow-lg">
                        {{-- Responsive: Flex col di HP, Flex row di Tablet/Desktop --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                            <div>
                                <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Kesimpulan
                                    AI</span>
                                <h2 class="text-2xl font-bold text-white mt-1">
                                    @if ($report->status == 'danger')
                                        🚨 Perlu Perhatian
                                    @elseif($report->status == 'warning')
                                        ⚠️ Waspada
                                    @else
                                        ✅ Keuangan Sehat
                                    @endif
                                </h2>
                            </div>
                            <div class="text-left sm:text-right w-full sm:w-auto">
                                <span class="text-gray-400 text-xs">Total Pengeluaran</span>
                                <div class="text-xl font-bold text-white break-all">Rp
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

                    {{-- Card Analisis Naratif --}}
                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-file-alt text-blue-400 mr-2"></i> Detail Analisis
                        </h3>
                        <p
                            class="text-gray-300 text-sm leading-relaxed whitespace-pre-line border-l-4 border-gray-600 pl-4">
                            {{ $report->ai_analysis }}
                        </p>
                    </div>

                    {{-- Card Rekomendasi --}}
                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i> Rekomendasi Aksi
                        </h3>
                        <div class="space-y-3">
                            @foreach (explode("\n", $report->ai_recommendation) as $saran)
                                @if (trim($saran))
                                    <div class="flex items-start bg-gray-900/50 p-3 rounded-lg">
                                        <i
                                            class="fas fa-check-circle text-green-500 mt-1 mr-3 text-xs flex-shrink-0"></i>
                                        <p class="text-gray-300 text-sm">
                                            {{ str_replace(['-', '*'], '', trim($saran)) }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Kalender Visual --}}
                <div class="lg:col-span-1">
                    {{-- Sticky hanya di layar besar (lg) --}}
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 lg:sticky lg:top-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-white font-bold text-lg">Kalender Aktivitas</h3>
                            <div class="flex gap-2">
                                {{-- Legend: Boros (Merah) --}}
                                <span class="text-[10px] sm:text-xs text-gray-400 flex items-center">
                                    <span
                                        class="w-2 h-2 bg-red-500 rounded-full mr-1 shadow-[0_0_5px_red]"></span>Boros
                                </span>
                                {{-- Legend: Ada Transaksi (Hijau) --}}
                                <span class="text-[10px] sm:text-xs text-gray-400 flex items-center">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-1"></span>Ada Transaksi
                                </span>
                            </div>
                        </div>

                        {{-- Navigasi Bulan --}}
                        <div class="flex justify-between items-center mb-4 text-sm text-gray-300">
                            <button id="prevMonth" class="hover:text-white p-1"><i
                                    class="fas fa-chevron-left"></i></button>
                            <span id="currentMonthYear" class="font-bold"></span>
                            <button id="nextMonth" class="hover:text-white p-1"><i
                                    class="fas fa-chevron-right"></i></button>
                        </div>

                        <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs text-gray-500">
                            <div>Min</div>
                            <div>Sen</div>
                            <div>Sel</div>
                            <div>Rab</div>
                            <div>Kam</div>
                            <div>Jum</div>
                            <div>Sab</div>
                        </div>

                        {{-- Grid Tanggal (Diisi JS) --}}
                        <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>

                        @if (!empty($report->wasteful_dates))
                            <div class="mt-4 p-3 bg-red-900/20 border border-red-500/30 rounded-xl">
                                <p class="text-xs text-red-300 text-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Terdeteksi {{ count($report->wasteful_dates) }} hari dengan pengeluaran tinggi.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20 text-center px-4">
                <div class="bg-gray-800 p-6 rounded-full mb-4 animate-pulse">
                    <i class="fas fa-magic text-4xl text-gray-500"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Siap Menganalisis?</h3>
                <p class="text-gray-400 mt-2 max-w-md text-sm sm:text-base">Pilih periode di atas dan klik tombol
                    "Mulai Analisis AI" untuk mendapatkan laporan lengkap.</p>
            </div>
        @endif

        {{-- 5. TABEL RIWAYAT (HISTORY) --}}
        @if (isset($history) && $history->count() > 0)
            <div class="mt-12 animate-fade-in-up">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-history text-gray-400 mr-3"></i> Riwayat Analisis
                </h3>

                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                                <tr>
                                    <th scope="col" class="px-4 sm:px-6 py-4 whitespace-nowrap">Periode</th>
                                    <th scope="col" class="px-4 sm:px-6 py-4 whitespace-nowrap">Tipe</th>
                                    <th scope="col" class="px-4 sm:px-6 py-4 whitespace-nowrap">Pengeluaran</th>
                                    <th scope="col" class="px-4 sm:px-6 py-4 whitespace-nowrap">Status</th>
                                    <th scope="col" class="px-4 sm:px-6 py-4 whitespace-nowrap">Dibuat Pada</th>
                                    <th scope="col" class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($history as $h)
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition">
                                        <td class="px-4 sm:px-6 py-4 font-medium text-white whitespace-nowrap">
                                            @if ($h->type == 'weekly')
                                                Minggu ke-{{ substr($h->period_key, -2) }}
                                                ({{ substr($h->period_key, 0, 4) }})
                                            @else
                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $h->period_key)->format('F Y') }}
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $h->type == 'weekly' ? 'bg-blue-900/30 text-blue-400' : 'bg-purple-900/30 text-purple-400' }}">
                                                {{ ucfirst($h->type == 'weekly' ? 'Mingguan' : 'Bulanan') }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">Rp
                                            {{ number_format($h->total_expense, 0, ',', '.') }}</td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            @if ($h->status == 'danger')
                                                <span class="text-red-400 flex items-center"><i
                                                        class="fas fa-exclamation-circle mr-1"></i> Bahaya</span>
                                            @elseif($h->status == 'warning')
                                                <span class="text-yellow-400 flex items-center"><i
                                                        class="fas fa-exclamation-triangle mr-1"></i> Waspada</span>
                                            @else
                                                <span class="text-green-400 flex items-center"><i
                                                        class="fas fa-check-circle mr-1"></i> Aman</span>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            {{ $h->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                            <a href="{{ route('reports.show', $h->id) }}"
                                                class="text-[#ff6b00] hover:text-white font-medium hover:underline transition flex justify-center items-center">
                                                Detail <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- 6. JAVASCRIPT & LOGIKA KALENDER --}}
    @push('scripts')
        <script>
            // 1. FUNGSI LOADING
            function showLoading(form) {
                const btn = form.querySelector('button[type="submit"]');
                const text = document.getElementById('btn-text');
                const loader = document.getElementById('btn-loader');

                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('cursor-not-allowed', 'opacity-75');
                }

                if (text) text.classList.add('hidden');
                if (loader) {
                    loader.classList.remove('hidden');
                    loader.classList.add('flex');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // 2. CEK APAKAH KALENDER ADA
                const calendarGrid = document.getElementById('calendarGrid');
                if (!calendarGrid) {
                    return;
                }

                // 3. SETUP DATA
                // 1. AMBIL DATA DARI PHP
                const wastefulDates = {!! json_encode($report->wasteful_dates ?? []) !!};

                // [PENTING] Baris ini menangkap data yang dikirim Controller
                const expenseDates = {!! json_encode($expenseDates ?? []) !!};

                const rawDate = "{{ $date ?? date('Y-m-d') }}";
                const refDate = new Date(rawDate);

                const currentMonthYear = document.getElementById('currentMonthYear');
                const prevBtn = document.getElementById('prevMonth');
                const nextBtn = document.getElementById('nextMonth');

                let currentDate = new Date(refDate);

                // 4. FUNGSI RENDER KALENDER
                function renderCalendar(date) {
                    calendarGrid.innerHTML = '';
                    const year = date.getFullYear();
                    const month = date.getMonth();

                    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                        "September", "Oktober", "November", "Desember"
                    ];

                    if (currentMonthYear) {
                        currentMonthYear.textContent = `${monthNames[month]} ${year}`;
                    }

                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    // Padding Awal
                    for (let i = 0; i < firstDay; i++) {
                        const cell = document.createElement('div');
                        calendarGrid.appendChild(cell);
                    }

                    // Loop Hari
                    for (let i = 1; i <= daysInMonth; i++) {
                        const currentMonthStr = String(month + 1).padStart(2, '0');
                        const currentDayStr = String(i).padStart(2, '0');
                        const dateString = `${year}-${currentMonthStr}-${currentDayStr}`;

                        // CEK STATUS TANGGAL
                        const isWasteful = wastefulDates.includes(dateString);
                        const hasExpense = expenseDates.includes(dateString); // Cek ada transaksi?

                        const today = new Date();
                        const isToday = (i === today.getDate() && month === today.getMonth() && year === today
                            .getFullYear());

                        const cell = document.createElement('div');

                        // Default Style
                        let bgClass = 'text-gray-300 hover:bg-gray-700';

                        // Highlight Hari Ini (Background Kotak)
                        if (isToday) {
                            bgClass = 'bg-gray-700 text-white font-bold border border-blue-500/50';
                        }

                        cell.className =
                            `h-9 w-full rounded-lg flex flex-col items-center justify-center text-xs relative cursor-default transition ${bgClass}`;
                        cell.innerText = i;

                        // LOGIKA DOT (TITIK INDIKATOR)
                        if (isWasteful) {
                            // PRIORITAS 1: BOROS (Merah)
                            const dot = document.createElement('div');
                            dot.className = "w-1.5 h-1.5 rounded-full mt-0.5 bg-red-500 shadow-[0_0_5px_red]";
                            cell.appendChild(dot);
                        } else if (hasExpense) {
                            // PRIORITAS 2: ADA TRANSAKSI (Hijau/Emerald)
                            const dot = document.createElement('div');
                            dot.className = "w-1.5 h-1.5 rounded-full mt-0.5 bg-emerald-500";
                            cell.appendChild(dot);
                        } else if (isToday) {
                            // PRIORITAS 3: HARI INI TAPI KOSONG (Biru)
                            const dot = document.createElement('div');
                            dot.className = "w-1.5 h-1.5 rounded-full mt-0.5 bg-blue-400";
                            cell.appendChild(dot);
                        }

                        calendarGrid.appendChild(cell);
                    }
                }

                // 5. EVENT LISTENER NAVIGASI
                if (prevBtn) {
                    prevBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentDate.setMonth(currentDate.getMonth() - 1);
                        renderCalendar(currentDate);
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentDate.setMonth(currentDate.getMonth() + 1);
                        renderCalendar(currentDate);
                    });
                }

                // Render Awal
                renderCalendar(currentDate);
            });
        </script>
    @endpush
</x-app-layout>
