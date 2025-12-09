<x-app-layout>
    <x-slot name="header-title">Dashboard</x-slot>
    {{-- <x-slot name="header-subtitle">Welcome back, {{ Auth::user()->name }}!</x-slot> --}}

    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Balance -->
            <div class="stat-card p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-700 rounded-xl">
                        <i class="fas fa-wallet text-orange text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-400">This Month</span>
                </div>
                {{-- <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</h3> --}}
                <p class="text-gray-400">Net Balance</p>
                <div class="mt-4 flex items-center text-sm">
                    @php
                        $percentage = $totalIncome > 0 ? (($totalIncome - $totalExpense) / $totalIncome) * 100 : 0;
                        $isPositive = $percentage >= 0;
                    @endphp
                    <span class="flex items-center {{ $isPositive ? 'text-green-400' : 'text-red-400' }}">
                        <i class="fas fa-arrow-{{ $isPositive ? 'up' : 'down' }} mr-1"></i>
                        {{ number_format(abs($percentage), 1) }}%
                    </span>
                    <span class="text-gray-500 ml-2">from last month</span>
                </div>
            </div>

            <!-- Total Income -->
            <div class="stat-card p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-700 rounded-xl">
                        <i class="fas fa-money-bill-wave text-green-400 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-400">This Month</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                <p class="text-gray-400">Total Income</p>
                <div class="mt-2">
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full"
                            style="width: {{ $totalIncome > 0 ? min(100, ($totalIncome / ($totalIncome + $totalExpense)) * 100) : 0 }}%">
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Expense -->
            <div class="stat-card p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-700 rounded-xl">
                        <i class="fas fa-receipt text-red-400 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-400">This Month</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                <p class="text-gray-400">Total Expenses</p>
                <div class="mt-2">
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full"
                            style="width: {{ $totalExpense > 0 ? min(100, ($totalExpense / ($totalIncome + $totalExpense)) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- This Week Average -->
            <div class="stat-card p-6 rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gray-700 rounded-xl">
                        <i class="fas fa-chart-line text-purple-400 text-xl"></i>
                    </div>
                    <span class="text-sm text-gray-400">This Week</span>
                </div>
                <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($weeklyAverage, 0, ',', '.') }}</h3>
                <p class="text-gray-400">Average Daily</p>
                {{-- @if ($weeklySummary)
                <div class="mt-4">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $weeklySummary->average_expense > $weeklySummary->total_expense_this_week ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                        {{ $weeklySummary->average_expense > $weeklySummary->total_expense_this_week ? 'Below Avg' : 'Above Avg' }}
                    </span>
                </div>
                @endif --}}
            </div>
        </div>
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Kalender Aktivitas</h3>

                <div class="flex items-center space-x-2">
                    <button id="prevMonth" class="p-1 text-gray-400 hover:text-white transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span id="currentMonthYear" class="text-sm font-medium text-white min-w-[100px] text-center"></span>
                    <button id="nextMonth" class="p-1 text-gray-400 hover:text-white transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-1 mb-2 text-center">
                <div class="text-xs text-gray-500 font-medium py-1">Min</div>
                <div class="text-xs text-gray-500 font-medium py-1">Sen</div>
                <div class="text-xs text-gray-500 font-medium py-1">Sel</div>
                <div class="text-xs text-gray-500 font-medium py-1">Rab</div>
                <div class="text-xs text-gray-500 font-medium py-1">Kam</div>
                <div class="text-xs text-gray-500 font-medium py-1">Jum</div>
                <div class="text-xs text-gray-500 font-medium py-1">Sab</div>
            </div>

            <div id="calendarGrid" class="grid grid-cols-7 gap-1">
            </div>
        </div>

        <div id="actionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                    id="modalBackdrop"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-gray-700 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-700">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange/10 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-calendar-day text-[#ff6b00]"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-white" id="modalDateTitle">
                                    Detail Tanggal
                                </h3>

                                <div id="existingDataContainer" class="mt-4 text-left hidden">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Riwayat
                                        Hari Ini</h4>

                                    <div id="existingDataList"
                                        class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                    </div>

                                    <hr class="border-gray-700 my-4">
                                </div>

                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">
                                        Ingin menambah catatan baru?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-col gap-3">
                        <a id="btnExpense" href="#"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#ff6b00] text-base font-medium text-white hover:bg-[#ff8c42] focus:outline-none sm:text-sm transition">
                            <i class="fas fa-receipt mr-2 mt-1"></i> Tambah Pengeluaran
                        </a>
                        <a id="btnActivity" href="#"
                            class="w-full inline-flex justify-center rounded-xl border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none sm:text-sm transition">
                            <i class="fas fa-map-marker-alt mr-2 mt-1"></i> Tambah Aktivitas
                        </a>
                        <button type="button" id="closeModal"
                            class="mt-2 w-full inline-flex justify-center text-sm text-gray-500 hover:text-gray-300">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Charts & Graphs -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Expense Chart -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">Expense Trend</h3>
                    <select
                        class="text-sm bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange">
                        <option class="bg-gray-700">This Month</option>
                        <option class="bg-gray-700">Last Month</option>
                        <option class="bg-gray-700">This Year</option>
                    </select>
                </div>
                <div class="h-80">
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">Spending by Category</h3>
                    <span class="text-sm text-gray-400">This Month</span>
                </div>
                <div class="space-y-4">
                    {{-- @foreach ($categoryBreakdown as $category)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-700 rounded-xl transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center">
                                <i class="{{ $category['icon'] }} text-lg" style="color: {{ $category['color'] }};"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-white"></h4>
                                <p class="text-sm text-gray-400"> transactions</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-white">Rp </p>
                            <p class="text-sm text-gray-400">%</p>
                        </div>
                    </div>
                    @endforeach --}}
                </div>
            </div>
        </div>

        <!-- Recent Activities & Recommendations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activities -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">Recent Activities</h3>
                    <a href="" class="text-sm text-orange hover:text-orange-400">View All</a>
                </div>
                <div class="space-y-4">
                    {{-- @forelse($recentActivities as $activity)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-700 rounded-xl transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-orange"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-white"></h4>
                                <p class="text-sm text-gray-400">
                                    <i class="far fa-clock mr-1"></i>
                                  
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-300">{{ number_format($activity->distance_in_km ?? 0, 1) }} km</p>
                            @if ($activity->cost_to_there)
                            <p class="text-sm font-medium text-red-400">Rp {{ number_format($activity->cost_to_there, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-map-marker-alt text-gray-600 text-4xl mb-3"></i>
                        <p class="text-gray-400">No activities recorded</p>
                        <a href="{{ route('activities.create') }}" class="text-orange hover:text-orange-400 text-sm mt-2 inline-block">Add your first activity</a>
                    </div>
                    @endforelse --}}
                </div>
            </div>

            <!-- Recommendations -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">Smart Recommendations</h3>
                    <i class="fas fa-lightbulb text-yellow-500 text-xl"></i>
                </div>
                <div class="space-y-4">
                    {{-- @forelse($recommendations as $rec)
                    <div class="p-4 bg-gradient-to-r from-gray-700 to-gray-800 rounded-xl border border-gray-600">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-bullhorn text-orange mt-1"></i>
                            </div>
                            <div>
                                <p class="text-gray-300">{{ $rec->message }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="far fa-calendar mr-1"></i>
                                    Week of {{ \Carbon\Carbon::parse($rec->week_start)->format('M d') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-lightbulb text-gray-600 text-4xl mb-3"></i>
                        <p class="text-gray-400">No recommendations yet</p>
                        <p class="text-sm text-gray-500 mt-1">Complete more transactions to get personalized tips</p>
                    </div>
                    @endforelse --}}
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href=""
                    class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-xl hover:border-orange hover:bg-gray-700 transition group">
                    <div
                        class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mb-3 group-hover:bg-red-900 transition">
                        <i class="fas fa-minus-circle text-red-400 text-xl"></i>
                    </div>
                    <span class="font-medium text-white">Add Expense</span>
                    <span class="text-sm text-gray-400">Record spending</span>
                </a>

                <a href=""
                    class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-xl hover:border-orange hover:bg-gray-700 transition group">
                    <div
                        class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mb-3 group-hover:bg-green-900 transition">
                        <i class="fas fa-plus-circle text-green-400 text-xl"></i>
                    </div>
                    <span class="font-medium text-white">Add Income</span>
                    <span class="text-sm text-gray-400">Record earnings</span>
                </a>

                <a href=""
                    class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-xl hover:border-orange hover:bg-gray-700 transition group">
                    <div
                        class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-900 transition">
                        <i class="fas fa-map-marker-alt text-blue-400 text-xl"></i>
                    </div>
                    <span class="font-medium text-white">Log Activity</span>
                    <span class="text-sm text-gray-400">Track movement</span>
                </a>

                <a href=""
                    class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-xl hover:border-orange hover:bg-gray-700 transition group">
                    <div
                        class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-900 transition">
                        <i class="fas fa-chart-pie text-purple-400 text-xl"></i>
                    </div>
                    <span class="font-medium text-white">View Reports</span>
                    <span class="text-sm text-gray-400">Analytics & insights</span>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. TERIMA DATA DARI CONTROLLER (Blade ke JS)
                // Pastikan variabel $calendarData dikirim dari controller
                const dbData = {!! json_encode($calendarData ?? []) !!};

                const calendarGrid = document.getElementById('calendarGrid');
                const currentMonthYear = document.getElementById('currentMonthYear');
                const prevBtn = document.getElementById('prevMonth');
                const nextBtn = document.getElementById('nextMonth');

                // Modal Elements
                const modal = document.getElementById('actionModal');
                const modalTitle = document.getElementById('modalDateTitle');
                const btnExpense = document.getElementById('btnExpense');
                const btnActivity = document.getElementById('btnActivity');
                const closeModal = document.getElementById('closeModal');
                const modalBackdrop = document.getElementById('modalBackdrop');

                // Elemen Baru untuk List Data
                const existingDataContainer = document.getElementById('existingDataContainer');
                const existingDataList = document.getElementById('existingDataList');

                let currentDate = new Date();

                function renderCalendar(date) {
                    calendarGrid.innerHTML = '';
                    const year = date.getFullYear();
                    const month = date.getMonth();

                    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                        "September", "Oktober", "November", "Desember"
                    ];
                    currentMonthYear.textContent = `${monthNames[month]} ${year}`;

                    // Hari pertama bulan ini (0 = Minggu, 1 = Senin, dst)
                    const firstDayIndex = new Date(year, month, 1).getDay();

                    // Jumlah hari di bulan ini
                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    // Jumlah hari di bulan SEBELUMNYA (untuk mengisi padding awal)
                    const daysInPrevMonth = new Date(year, month, 0).getDate();

                    const today = new Date();

                    // 1. RENDER TANGGAL DARI BULAN SEBELUMNYA (PADDING AWAL)
                    // Loop mundur dari hari pertama
                    for (let x = firstDayIndex; x > 0; x--) {
                        const dayNum = daysInPrevMonth - x + 1;
                        const dayCell = document.createElement('div');

                        // Styling: Teks abu-abu gelap (supaya terlihat non-aktif)
                        dayCell.className =
                            'h-10 w-full rounded-lg text-sm flex flex-col items-center justify-center text-gray-600 cursor-default';

                        const daySpan = document.createElement('span');
                        daySpan.textContent = dayNum;
                        dayCell.appendChild(daySpan);

                        calendarGrid.appendChild(dayCell);
                    }

                    // 2. RENDER TANGGAL BULAN INI (Sama seperti kode Anda sebelumnya)
                    for (let i = 1; i <= daysInMonth; i++) {
                        const dayCell = document.createElement('button');

                        // Format Key Tanggal (YYYY-MM-DD)
                        const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                        const hasData = dbData[dateKey] !== undefined;

                        // Styling Dasar
                        let cellClass =
                            `h-10 w-full rounded-lg text-sm flex flex-col items-center justify-center transition relative `;

                        // Highlight Hari Ini
                        if (i === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                            cellClass += 'bg-[#ff6b00] text-white font-bold shadow-lg shadow-orange-500/30';
                        } else {
                            cellClass += 'text-gray-300 hover:bg-gray-700 hover:text-[#ff6b00]';
                        }

                        dayCell.className = cellClass;

                        const dayNumber = document.createElement('span');
                        dayNumber.textContent = i;
                        dayCell.appendChild(dayNumber);

                        // Dot Penanda
                        if (hasData) {
                            const dot = document.createElement('div');
                            const dotColor = (i === today.getDate() && month === today.getMonth()) ? 'bg-white' :
                                'bg-[#ff6b00]';
                            dot.className = `w-1.5 h-1.5 rounded-full mt-0.5 ${dotColor}`;
                            dayCell.appendChild(dot);
                        }

                        // Event Klik
                        dayCell.addEventListener('click', () => {
                            openModal(dateKey, i, monthNames[month], dbData[dateKey]);
                        });

                        calendarGrid.appendChild(dayCell);
                    }

                    // 3. RENDER TANGGAL BULAN DEPAN (PADDING AKHIR)
                    // Hitung sisa kotak biar grid tetap rapi (sampai akhir minggu)
                    const totalCellsSoFar = firstDayIndex + daysInMonth;
                    // Kita ingin mengisi baris sampai penuh (modulus 7)
                    // Jika sisa bagi 7 tidak 0, berarti baris belum penuh
                    const nextMonthDays = (7 - (totalCellsSoFar % 7)) % 7;

                    for (let j = 1; j <= nextMonthDays; j++) {
                        const dayCell = document.createElement('div');
                        // Styling: Teks abu-abu gelap
                        dayCell.className =
                            'h-10 w-full rounded-lg text-sm flex flex-col items-center justify-center text-gray-600 cursor-default';

                        const daySpan = document.createElement('span');
                        daySpan.textContent = j;
                        dayCell.appendChild(daySpan);

                        calendarGrid.appendChild(dayCell);
                    }
                }

                function openModal(dateString, day, monthName, dayData) {
                    modalTitle.textContent = `Tanggal ${day} ${monthName}`;

                    // 1. Reset state: Kosongkan list dan SEMBUNYIKAN container dulu
                    existingDataList.innerHTML = '';
                    existingDataContainer.classList.add('hidden'); // Paksa hidden dulu

                    // 2. Cek apakah dayData ada isinya?
                    if (dayData && (dayData.expenses || dayData.activities)) {
                        console.log("Menampilkan data untuk:", dateString); // Debug log

                        // 3. Hapus class hidden AGAR MUNCUL
                        existingDataContainer.classList.remove('hidden');

                        // Render Pengeluaran
                        if (dayData.expenses) {
                            dayData.expenses.forEach(exp => {
                                const item = document.createElement('div');
                                item.className =
                                    'flex justify-between items-center text-sm p-3 bg-gray-700/50 rounded-lg border border-gray-600 mb-2';
                                item.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-red-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-receipt text-red-400 text-xs"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-gray-200 font-medium">${exp.category}</span>
                            <span class="text-gray-500 text-xs">${exp.desc ? exp.desc : '-'}</span>
                        </div>
                    </div>
                    <span class="font-bold text-white text-xs">Rp ${exp.amount}</span>
                `;
                                existingDataList.appendChild(item);
                            });
                        }

                        // Render Aktivitas (sama seperti sebelumnya...)
                        if (dayData.activities) {
                            dayData.activities.forEach(act => {
                                const item = document.createElement('div');
                                item.className =
                                    'flex justify-between items-center text-sm p-3 bg-gray-700/50 rounded-lg border border-gray-600 mb-2';
                                item.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-map-marker-alt text-blue-400 text-xs"></i>
                        </div>
                        <span class="text-gray-200 font-medium">${act.title}</span>
                    </div>
                `;
                                existingDataList.appendChild(item);
                            });
                        }
                    } else {
                        console.log("Tidak ada data untuk tanggal ini.");
                    }

                    // Update Link Tombol Tambah
                    btnExpense.href = `{{ route('expenses.create') }}?date=${dateString}`;
                    btnActivity.href = `#`;

                    modal.classList.remove('hidden');
                }

                function hideModal() {
                    modal.classList.add('hidden');
                }

                // Navigasi Bulan
                prevBtn.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    renderCalendar(currentDate);
                });

                nextBtn.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    renderCalendar(currentDate);
                });

                closeModal.addEventListener('click', hideModal);
                modalBackdrop.addEventListener('click', hideModal);

                renderCalendar(currentDate);
            });
        </script>
        <script>
            // Expense Chart dengan theme dark
            const ctx = document.getElementById('expenseChart').getContext('2d');

            // Chart.js dark theme
            Chart.defaults.color = '#9ca3af';
            Chart.defaults.borderColor = '#374151';

            const expenseChart = new Chart(ctx, {
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
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#374151',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#374151',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#9ca3af',
                                callback: function(value) {
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: '#374151',
                                display: false
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        }
                    }
                }
            });

            // Update stats every 60 seconds
            setInterval(() => {
                // You can add real-time updates here
                console.log('Updating dashboard stats...');
            }, 60000);
        </script>
    @endpush
</x-app-layout>
