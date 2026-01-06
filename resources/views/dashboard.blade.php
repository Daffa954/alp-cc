<x-app-layout>
    <x-slot name="header-title">Dashboard</x-slot>
    {{-- <x-slot name="header-subtitle">Welcome back, {{ Auth::user()->name }}!</x-slot> --}}

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stat-card p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-700 rounded-xl">
                        <i class="fas fa-wallet text-orange text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-400">This Month</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                <p class="text-gray-400">Net Balance</p>
            </div>

            <div class="stat-card p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-700 rounded-xl">
                        <i class="fas fa-money-bill-wave text-green-400 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-400">This Month</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                <p class="text-gray-400">Total Income</p>
            </div>

            <div class="stat-card p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-700 rounded-xl">
                        <i class="fas fa-receipt text-red-400 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-400">This Month</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                <p class="text-gray-400">Total Expenses</p>
            </div>

            <div class="stat-card p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-700 rounded-xl">
                        <i class="fas fa-chart-line text-purple-400 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-400">This Week</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($weeklyAverage, 0, ',', '.') }}</h3>
                <p class="text-gray-400">Average Daily</p>
            </div>
        </div>

        <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-6">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold text-white">Aktivitas Keuangan</h3>
                        <p class="text-xs text-gray-400">Pantau catatan harian dan bulanan Anda.</p>
                    </div>

                    <div class="flex justify-center">
                        <div class="flex p-1 bg-gray-900/50 rounded-xl border border-gray-700 w-fit">
                            <button id="tabBtnMonthly"
                                class="px-5 py-2 rounded-lg text-sm font-medium transition bg-[#ff6b00] text-white">
                                Bulanan
                            </button>
                            <button id="tabBtnDaily"
                                class="px-5 py-2 rounded-lg text-sm font-medium transition text-gray-400 hover:text-white">
                                Harian
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-center md:justify-end">
                        <div id="calendarNav"
                            class="flex items-center space-x-3 bg-gray-700/30 p-1.5 rounded-xl border border-gray-600">
                            <button id="prevMonth"
                                class="p-1.5 text-gray-400 hover:text-white transition bg-gray-800 rounded-lg">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <span id="currentMonthYear"
                                class="text-xs font-bold text-white min-w-[100px] text-center uppercase tracking-wider"></span>
                            <button id="nextMonth"
                                class="p-1.5 text-gray-400 hover:text-white transition bg-gray-800 rounded-lg">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div id="monthlyView">
                    <div
                        class="grid grid-cols-7 gap-1 mb-2 text-center text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                        <div>Min</div>
                        <div>Sen</div>
                        <div>Sel</div>
                        <div>Rab</div>
                        <div>Kam</div>
                        <div>Jum</div>
                        <div>Sab</div>
                    </div>
                    <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>
                </div>

                <div id="dailyView" class="hidden animate-fade-in">
                    <div class="flex flex-col items-center justify-center mb-8 gap-4">
                        <h4 id="dailySelectedDate" class="text-2xl font-black text-white tracking-tight">Detail Tanggal
                        </h4>
                        <div class="flex flex-wrap justify-center gap-3">
                            <a id="btnDailyExpense" href="#"
                                class="px-4 py-2 bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition">
                                <i class="fas fa-minus-circle mr-2"></i> Expense
                            </a>
                            <a id="btnDailyIncome" href="#"
                                class="px-4 py-2 bg-green-500/10 text-green-400 border border-green-500/20 rounded-xl text-xs font-bold hover:bg-green-500 hover:text-white transition">
                                <i class="fas fa-plus-circle mr-2"></i> Income
                            </a>
                            <a id="btnDailyActivity" href="#"
                                class="px-4 py-2 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-xl text-xs font-bold hover:bg-blue-500 hover:text-white transition">
                                <i class="fas fa-running mr-2"></i> Activities
                            </a>
                        </div>
                    </div>
                    <div id="dailyListContainer" class="space-y-4"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 h-96">
                <h3 class="text-lg font-semibold text-white mb-6 text-center">Expense Trend</h3>
                <canvas id="expenseChart"></canvas>
            </div>

            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 overflow-y-auto max-h-96">
                <h3 class="text-lg font-semibold text-white mb-6 text-center">Smart Recommendations</h3>
                <div class="space-y-4">
                    @forelse($recommendations as $rec)
                        <div class="p-4 bg-orange/5 border border-orange/10 rounded-xl flex items-start space-x-3">
                            <i class="fas fa-lightbulb text-yellow-500 mt-1"></i>
                            <p class="text-sm text-gray-300">{{ $rec->message }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 text-sm">No recommendations yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-8">
            <h3 class="text-lg font-semibold text-white mb-8 text-center uppercase tracking-widest">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto">
                <a href="{{ route('expenses.create') }}"
                    class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-2xl hover:border-orange hover:bg-gray-700/50 transition-all group">
                    <div
                        class="w-14 h-14 bg-red-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-500 group-hover:scale-110 transition-all">
                        <i class="fas fa-minus-circle text-red-400 text-2xl group-hover:text-white"></i>
                    </div>
                    <span class="font-bold text-white text-sm">Add Expense</span>
                </a>
                <a href="{{ route('incomes.create') }}"
                    class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-2xl hover:border-orange hover:bg-gray-700/50 transition-all group">
                    <div
                        class="w-14 h-14 bg-green-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-green-500 group-hover:scale-110 transition-all">
                        <i class="fas fa-plus-circle text-green-400 text-2xl group-hover:text-white"></i>
                    </div>
                    <span class="font-bold text-white text-sm">Add Income</span>
                </a>
                <a href="{{ route('activities.create') }}"
                    class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-2xl hover:border-orange hover:bg-gray-700/50 transition-all group">
                    <div
                        class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-500 group-hover:scale-110 transition-all">
                        <i class="fas fa-walking text-blue-400 text-2xl group-hover:text-white"></i>
                    </div>
                    <span class="font-bold text-white text-sm">Log Activity</span>
                </a>
                <a href="{{ route('reports.index') }}"
                    class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-2xl hover:border-orange hover:bg-gray-700/50 transition-all group">
                    <div
                        class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:scale-110 transition-all">
                        <i class="fas fa-chart-pie text-purple-400 text-2xl group-hover:text-white"></i>
                    </div>
                    <span class="font-bold text-white text-sm">Analytics</span>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dbData = {!! json_encode($calendarData ?? []) !!};

                // --- KOREKSI 1: Ambil Tanggal dari Controller ---
                const serverDateRaw = "{{ $dateContext->format('Y-m-d') }}";
                let currentDate = new Date(serverDateRaw); // Mulai dari bulan yg dipilih user (bukan hari ini)

                const calendarGrid = document.getElementById('calendarGrid');
                const currentMonthYear = document.getElementById('currentMonthYear');
                const dailyListContainer = document.getElementById('dailyListContainer');
                const dailySelectedDate = document.getElementById('dailySelectedDate');

                const monthlyView = document.getElementById('monthlyView');
                const dailyView = document.getElementById('dailyView');
                const calendarNav = document.getElementById('calendarNav');
                const tabBtnMonthly = document.getElementById('tabBtnMonthly');
                const tabBtnDaily = document.getElementById('tabBtnDaily');
                const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                    "September", "Oktober", "November", "Desember"
                ];

                // --- KOREKSI 2: Fungsi Navigasi dengan Reload ---
                function navigateMonth(offset) {
                    // Geser bulan
                    const targetDate = new Date(currentDate);
                    targetDate.setMonth(targetDate.getMonth() + offset);

                    // Ambil bulan & tahun (Ingat: getMonth() JS itu 0-11, PHP butuh 1-12)
                    const m = targetDate.getMonth() + 1;
                    const y = targetDate.getFullYear();

                    // Reload halaman dengan parameter baru
                    const url = new URL(window.location.href);
                    url.searchParams.set('month', m);
                    url.searchParams.set('year', y);
                    window.location.href = url.toString();
                }

                // Pasang Event Listener ke tombol
                document.getElementById('prevMonth').onclick = () => navigateMonth(-1);
                document.getElementById('nextMonth').onclick = () => navigateMonth(1);


                // --- FUNGSI RENDER (Sama seperti sebelumnya, sedikit penyesuaian) ---
                function renderCalendar(date) {
                    calendarGrid.innerHTML = '';
                    const year = date.getFullYear();
                    const month = date.getMonth();
                    currentMonthYear.textContent = `${monthNames[month]} ${year}`;

                    const firstDayIndex = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const daysInPrevMonth = new Date(year, month, 0).getDate();

                    // Gunakan "Real Today" untuk highlight hari ini
                    const realToday = new Date();

                    for (let x = firstDayIndex; x > 0; x--) {
                        const cell = document.createElement('div');
                        cell.className =
                            'h-16 w-full rounded-xl flex items-center justify-center text-gray-700 bg-gray-900/10 text-[10px] font-bold';
                        cell.textContent = daysInPrevMonth - x + 1;
                        calendarGrid.appendChild(cell);
                    }

                    for (let i = 1; i <= daysInMonth; i++) {
                        const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                        const dayData = dbData[
                        dateKey]; // Data sekarang ada karena controller sudah mengirim bulan yang benar!

                        const isToday = i === realToday.getDate() && month === realToday.getMonth() && year ===
                            realToday.getFullYear();

                        const cell = document.createElement('button');
                        cell.className =
                            `min-h-[60px] w-full rounded-xl text-sm flex flex-col items-center justify-start p-1 transition border-2 ${isToday ? 'bg-orange/10 border-[#ff6b00] text-white font-bold' : 'bg-gray-700/20 border-transparent text-gray-400 hover:border-gray-500 hover:bg-gray-700/50'}`;
                        cell.innerHTML = `<span class="text-[10px] mb-1 font-bold">${i}</span>`;

                        if (dayData) {
                            if (dayData.total_income > 0) cell.innerHTML +=
                                `<span class="text-[8px] text-green-400 font-bold tracking-tighter">+${(dayData.total_income/1000).toFixed(0)}k</span>`;
                            if (dayData.total_expense > 0) cell.innerHTML +=
                                `<span class="text-[8px] text-red-400 font-bold tracking-tighter">-${(dayData.total_expense/1000).toFixed(0)}k</span>`;
                        }

                        cell.onclick = () => showDaily(dateKey);
                        calendarGrid.appendChild(cell);
                    }
                }

                // ... (Sisa fungsi showMonthly, showDaily, renderDailyList sama persis dengan kode Anda) ...

                function showMonthly() {
                    monthlyView.classList.remove('hidden');
                    dailyView.classList.add('hidden');
                    calendarNav.classList.remove('invisible');
                    tabBtnMonthly.className =
                        "px-5 py-2 rounded-lg text-sm font-medium transition bg-[#ff6b00] text-white";
                    tabBtnDaily.className =
                        "px-5 py-2 rounded-lg text-sm font-medium transition text-gray-400 hover:text-white";
                }

                function showDaily(dateKey) {
                    monthlyView.classList.add('hidden');
                    dailyView.classList.remove('hidden');
                    calendarNav.classList.add('invisible');
                    tabBtnDaily.className =
                        "px-5 py-2 rounded-lg text-sm font-medium transition bg-[#ff6b00] text-white";
                    tabBtnMonthly.className =
                        "px-5 py-2 rounded-lg text-sm font-medium transition text-gray-400 hover:text-white";
                    renderDailyList(dateKey);
                }

                function renderDailyList(dateKey) {
                    const dateObj = new Date(dateKey);
                    dailySelectedDate.textContent =
                        `${dateObj.getDate()} ${monthNames[dateObj.getMonth()]} ${dateObj.getFullYear()}`;

                    document.getElementById('btnDailyExpense').href = `{{ route('expenses.create') }}?date=${dateKey}`;
                    document.getElementById('btnDailyIncome').href = `{{ route('incomes.create') }}?date=${dateKey}`;
                    document.getElementById('btnDailyActivity').href =
                        `{{ route('activities.create') }}?date=${dateKey}`;

                    const data = dbData[dateKey];
                    dailyListContainer.innerHTML = '';

                    if (!data || (!data.expenses?.length && !data.activities?.length && !data.total_income)) {
                        dailyListContainer.innerHTML =
                            `<div class="text-center py-20 text-gray-500 border-2 border-dashed border-gray-700 rounded-3xl mx-auto w-full">Tidak ada transaksi pada tanggal ini.</div>`;
                        return;
                    }

                    let html = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="p-6 bg-green-500/5 border border-green-500/10 rounded-2xl text-center"><span class="text-xs text-green-400 font-bold uppercase">Total Income</span><p class="text-2xl font-black text-white mt-1">Rp ${new Intl.NumberFormat('id-ID').format(data.total_income || 0)}</p></div>
                            <div class="p-6 bg-red-500/5 border border-red-500/10 rounded-2xl text-center"><span class="text-xs text-red-400 font-bold uppercase">Total Expense</span><p class="text-2xl font-black text-white mt-1">Rp ${new Intl.NumberFormat('id-ID').format(data.total_expense || 0)}</p></div>
                        </div>`;

                    if (data.expenses) {
                        html += '<div class="space-y-3">';
                        data.expenses.forEach(exp => {
                            html += `
                                <div class="flex justify-between items-center p-4 bg-gray-700/50 rounded-2xl border border-gray-600 max-w-4xl mx-auto">
                                    <div class="flex items-center"><div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center mr-4"><i class="fas fa-receipt text-red-400"></i></div><div><p class="text-white font-bold">${exp.category}</p><p class="text-xs text-gray-400">${exp.desc || '-'}</p></div></div>
                                    <span class="text-red-400 font-bold">Rp ${exp.amount}</span>
                                </div>`;
                        });
                        html += '</div>';
                    }

                    if (data.activities) {
                        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-6 max-w-4xl mx-auto">';
                        data.activities.forEach(act => {
                            html +=
                                `<div class="flex items-center p-4 bg-gray-700/50 rounded-2xl border border-gray-600"><i class="fas fa-running text-blue-400 mr-3"></i><span class="text-white text-sm font-medium">${act.title}</span></div>`;
                        });
                        html += '</div>';
                    }

                    dailyListContainer.innerHTML = html;
                }

                tabBtnMonthly.onclick = showMonthly;
                // Default ke hari ini tapi dalam konteks bulan yang dipilih
                tabBtnDaily.onclick = () => showDaily(serverDateRaw);

                // Render Awal
                renderCalendar(currentDate);
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('expenseChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($expenseTrend['labels']) !!},
                    datasets: [{
                        label: 'Expenses',
                        data: {!! json_encode($expenseTrend['data']) !!},
                        borderColor: '#ff6b00',
                        backgroundColor: 'rgba(255, 107, 0, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ff6b00',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(55, 65, 81, 0.5)'
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 10
                                },
                                align: 'center'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            background: linear-gradient(145deg, #1f2937, #111827);
            border: 1px solid #374151;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: #ff6b00;
            transform: translateY(-2px);
        }
    </style>
</x-app-layout>
