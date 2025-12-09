<x-app-layout>
    <x-slot name="header-title">Laporan & Analisis</x-slot>
    <x-slot name="header-subtitle">Evaluasi keuangan dengan bantuan AI</x-slot>

    <div class="max-w-6xl mx-auto py-6">
        @if (session('success'))
            <div
                class="mb-6 bg-green-900/50 border border-green-500 text-green-200 px-4 py-3 rounded-xl flex items-center shadow-lg animate-fade-in-down">
                <i class="fas fa-check-circle text-xl mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded-xl flex items-center shadow-lg animate-fade-in-down">
                <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded-xl shadow-lg">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">

                <form action="{{ route('reports.index') }}" method="GET" class="w-full md:w-auto" id="filterForm">
                    <div class="flex flex-col sm:flex-row gap-3 items-center">

                        <div class="bg-gray-900 p-1 rounded-xl flex items-center border border-gray-600">
                            <label
                                class="cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition {{ $type == 'monthly' ? 'bg-[#ff6b00] text-white shadow-lg' : 'text-gray-400 hover:text-white' }}">
                                <input type="radio" name="type" value="monthly" class="hidden"
                                    onchange="this.form.submit()" {{ $type == 'monthly' ? 'checked' : '' }}>
                                Bulanan
                            </label>
                            <label
                                class="cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition {{ $type == 'weekly' ? 'bg-[#ff6b00] text-white shadow-lg' : 'text-gray-400 hover:text-white' }}">
                                <input type="radio" name="type" value="weekly" class="hidden"
                                    onchange="this.form.submit()" {{ $type == 'weekly' ? 'checked' : '' }}>
                                Mingguan
                            </label>
                        </div>

                        <div class="relative">
                            <input type="date" name="date" value="{{ $date }}"
                                onchange="this.form.submit()"
                                class="bg-gray-900 border border-gray-600 text-white rounded-xl px-4 py-2 text-sm focus:ring-[#ff6b00] focus:border-[#ff6b00] outline-none w-full sm:w-auto"
                                style="color-scheme: dark;">
                        </div>
                    </div>
                </form>

                <form action="{{ route('reports.generate') }}" method="POST" onsubmit="showLoading(this)">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <button type="submit" id="btn-generate"
                        class="group flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-[0_0_20px_rgba(59,130,246,0.5)] transition transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="btn-text" class="flex items-center">
                            <i class="fas fa-robot mr-2 animate-bounce-slow"></i>
                            {{ isset($report) ? 'Analisis Ulang' : 'Mulai Analisis AI' }}
                        </span>

                        <span id="btn-loader" class="hidden items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Sedang Menganalisis...
                        </span>
                    </button>
                </form>
            </div>
        </div>

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
                                    @if ($report->status == 'danger')
                                        🚨 Perlu Perhatian Serius
                                    @elseif($report->status == 'warning')
                                        ⚠️ Waspada
                                    @else
                                        ✅ Keuangan Sehat
                                    @endif
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
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-file-alt text-blue-400 mr-2"></i> Detail Analisis
                        </h3>
                        <p
                            class="text-gray-300 text-sm leading-relaxed whitespace-pre-line border-l-4 border-gray-600 pl-4">
                            {{ $report->ai_analysis }}
                        </p>
                    </div>

                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i> Rekomendasi Aksi
                        </h3>
                        <div class="space-y-3">
                            @foreach (explode("\n", $report->ai_recommendation) as $saran)
                                @if (trim($saran))
                                    <div class="flex items-start bg-gray-900/50 p-3 rounded-lg">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 text-xs"></i>
                                        <p class="text-gray-300 text-sm">
                                            {{ str_replace(['-', '*'], '', trim($saran)) }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 sticky top-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-white font-bold text-lg">Kalender Boros</h3>
                            <div class="flex gap-2">
                                <span class="text-xs text-gray-400 flex items-center"><span
                                        class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>Boros</span>
                                <span class="text-xs text-gray-400 flex items-center"><span
                                        class="w-2 h-2 bg-[#ff6b00] rounded-full mr-1"></span>Biasa</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-4 text-sm text-gray-300">
                            <button id="prevMonth" class="hover:text-white"><i class="fas fa-chevron-left"></i></button>
                            <span id="currentMonthYear" class="font-bold"></span>
                            <button id="nextMonth" class="hover:text-white"><i
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

                        <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>

                        @if (!empty($report->wasteful_dates))
                            <div class="mt-4 p-3 bg-red-900/20 border border-red-500/30 rounded-xl">
                                <p class="text-xs text-red-300 text-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Terdeteksi {{ count($report->wasteful_dates) }} hari dengan pengeluaran tidak
                                    wajar.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="bg-gray-800 p-6 rounded-full mb-4 animate-pulse">
                    <i class="fas fa-magic text-4xl text-gray-500"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Siap Menganalisis?</h3>
                <p class="text-gray-400 mt-2 max-w-md">Pilih periode di atas dan klik tombol "Mulai Analisis AI" untuk
                    mendapatkan laporan lengkap.</p>
            </div>
        @endif
    </div>

    @push('scripts')
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // 1. Cek apakah container kalender ada?
                    const calendarGrid = document.getElementById('calendarGrid');

                    // JIKA TIDAK ADA KALENDER (Belum ada report), STOP SCRIPT DI SINI
                    if (!calendarGrid) {
                        return;
                    }

                    // 2. Jika ada, lanjutkan inisialisasi
                    const wastefulDates = {!! json_encode($report->wasteful_dates ?? []) !!};

                    // Ambil tanggal dari PHP, pastikan valid. Jika kosong, pakai hari ini.
                    const rawDate = "{{ $date ?? date('Y-m-d') }}";
                    const refDate = new Date(rawDate);

                    const currentMonthYear = document.getElementById('currentMonthYear');
                    const prevBtn = document.getElementById('prevMonth');
                    const nextBtn = document.getElementById('nextMonth');

                    // Gunakan tanggal referensi untuk navigasi (bukan hardcode hari ini)
                    let currentDate = new Date(refDate);

                    function renderCalendar(date) {
                        calendarGrid.innerHTML = '';
                        const year = date.getFullYear();
                        const month = date.getMonth();

                        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                            "September", "Oktober", "November", "Desember"
                        ];

                        // Safety check untuk elemen judul bulan
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

                        // Render Tanggal
                        for (let i = 1; i <= daysInMonth; i++) {
                            // Format YYYY-MM-DD (Perhatikan timezone, gunakan local string component)
                            // Trik: Gunakan string manipulation agar tidak kena masalah timezone offset
                            const currentMonthStr = String(month + 1).padStart(2, '0');
                            const currentDayStr = String(i).padStart(2, '0');
                            const dateString = `${year}-${currentMonthStr}-${currentDayStr}`;

                            const isWasteful = wastefulDates.includes(dateString);

                            // Cek hari ini (local time)
                            const today = new Date();
                            const isToday = (i === today.getDate() && month === today.getMonth() && year === today
                                .getFullYear());

                            const cell = document.createElement('div');
                            // Styling
                            let bgClass = isToday ? 'bg-gray-700 text-white font-bold' : 'text-gray-300 hover:bg-gray-700';

                            cell.className =
                                `h-9 w-full rounded-lg flex flex-col items-center justify-center text-xs relative cursor-default transition ${bgClass}`;

                            // Span Angka
                            cell.innerText = i;

                            // Titik Indikator
                            if (isWasteful) {
                                const dot = document.createElement('div');
                                dot.className = "w-1.5 h-1.5 rounded-full mt-0.5 bg-red-500 shadow-[0_0_5px_red]";
                                cell.appendChild(dot);
                            } else if (isToday) {
                                const dot = document.createElement('div');
                                dot.className = "w-1.5 h-1.5 rounded-full mt-0.5 bg-blue-400";
                                cell.appendChild(dot);
                            }

                            calendarGrid.appendChild(cell);
                        }
                    }

                    // Event Listeners (Hanya dipasang jika tombol ada)
                    if (prevBtn) {
                        prevBtn.addEventListener('click', (e) => {
                            e.preventDefault(); // Mencegah form submit tidak sengaja
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

                    // Render Pertama Kali
                    renderCalendar(currentDate);
                });
            </script>
        @endpush
    @endpush
</x-app-layout>
