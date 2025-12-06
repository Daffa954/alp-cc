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
                    {{-- @php
                        $percentage = $totalIncome > 0 ? (($totalIncome - $totalExpense) / $totalIncome) * 100 : 0;
                        $isPositive = $percentage >= 0;
                    @endphp --}}
                    {{-- <span class="flex items-center {{ $isPositive ? 'text-green-400' : 'text-red-400' }}"> --}}
                        {{-- <i class="fas fa-arrow-{{ $isPositive ? 'up' : 'down' }} mr-1"></i> --}}
                        {{-- {{ number_format(abs($percentage), 1) }}% --}}
                    {{-- </span> --}}
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
                {{-- <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3> --}}
                <p class="text-gray-400">Total Income</p>
                <div class="mt-2">
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        {{-- <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalIncome > 0 ? min(100, ($totalIncome / ($totalIncome + $totalExpense)) * 100) : 0 }}%"></div> --}}
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
                {{-- <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3> --}}
                <p class="text-gray-400">Total Expenses</p>
                <div class="mt-2">
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        {{-- <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalExpense > 0 ? min(100, ($totalExpense / ($totalIncome + $totalExpense)) * 100) : 0 }}%"></div> --}}
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
                {{-- <h3 class="text-2xl font-bold text-white mb-1">Rp {{ number_format($weeklyAverage, 0, ',', '.') }}</h3> --}}
                <p class="text-gray-400">Average Daily</p>
                {{-- @if($weeklySummary)
                <div class="mt-4">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $weeklySummary->average_expense > $weeklySummary->total_expense_this_week ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                        {{ $weeklySummary->average_expense > $weeklySummary->total_expense_this_week ? 'Below Avg' : 'Above Avg' }}
                    </span>
                </div>
                @endif --}}
            </div>
        </div>

        <!-- Charts & Graphs -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Expense Chart -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">Expense Trend</h3>
                    <select class="text-sm bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange">
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
                    {{-- @foreach($categoryBreakdown as $category)
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
                            @if($activity->cost_to_there)
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
                <a href="" class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-xl hover:border-orange hover:bg-gray-700 transition group">
                    <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mb-3 group-hover:bg-red-900 transition">
                        <i class="fas fa-minus-circle text-red-400 text-xl"></i>
                    </div>
                    <span class="font-medium text-white">Add Expense</span>
                    <span class="text-sm text-gray-400">Record spending</span>
                </a>
                
                <a href="" class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-xl hover:border-orange hover:bg-gray-700 transition group">
                    <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mb-3 group-hover:bg-green-900 transition">
                        <i class="fas fa-plus-circle text-green-400 text-xl"></i>
                    </div>
                    <span class="font-medium text-white">Add Income</span>
                    <span class="text-sm text-gray-400">Record earnings</span>
                </a>
                
                <a href="" class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-xl hover:border-orange hover:bg-gray-700 transition group">
                    <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-900 transition">
                        <i class="fas fa-map-marker-alt text-blue-400 text-xl"></i>
                    </div>
                    <span class="font-medium text-white">Log Activity</span>
                    <span class="text-sm text-gray-400">Track movement</span>
                </a>
                
                <a href="" class="flex flex-col items-center justify-center p-6 border border-gray-700 rounded-xl hover:border-orange hover:bg-gray-700 transition group">
                    <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-900 transition">
                        <i class="fas fa-chart-pie text-purple-400 text-xl"></i>
                    </div>
                    <span class="font-medium text-white">View Reports</span>
                    <span class="text-sm text-gray-400">Analytics & insights</span>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- <script>
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
    </script> --}}
    @endpush
</x-app-layout>