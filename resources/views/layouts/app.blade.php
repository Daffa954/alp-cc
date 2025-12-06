{{-- 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FinanceTrack') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        .active-nav {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.1) 0%, transparent 100%);
            border-left: 4px solid #2563eb;
            color: #2563eb;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-white border-r border-gray-200 hidden md:block">
            <div class="p-6">
                <!-- Logo -->
                <div class="flex items-center space-x-3 mb-8">
                    <div class="bg-gradient-to-br from-blue-600 to-violet-600 w-10 h-10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">FinanceTrack</span>
                </div>

                <!-- User Profile -->
                <div class="flex items-center space-x-3 mb-8 p-3 bg-gray-50 rounded-xl">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-violet-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">{{ auth()->user()->name }}</h3>
                        <p class="text-xs text-gray-500">{{ auth()->user()->job ?: 'Set your job' }}</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition {{ request()->routeIs('dashboard') ? 'active-nav' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition">
                        <i class="fas fa-receipt w-5"></i>
                        <span>Expenses</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition">
                        <i class="fas fa-money-bill-wave w-5"></i>
                        <span>Income</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition">
                        <i class="fas fa-map-marker-alt w-5"></i>
                        <span>Activities</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Reports</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition">
                        <i class="fas fa-piggy-bank w-5"></i>
                        <span>Budgets</span>
                    </a>
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition">
                            <i class="fas fa-user-cog w-5"></i>
                            <span>Settings</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 w-full px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-xl transition">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
            <div class="flex justify-around py-3">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
                    <i class="fas fa-home text-gray-600"></i>
                    <span class="text-xs mt-1">Home</span>
                </a>
                <a href="" class="flex flex-col items-center">
                    <i class="fas fa-receipt text-gray-600"></i>
                    <span class="text-xs mt-1">Expenses</span>
                </a>
                <a href="}" class="flex flex-col items-center">
                    <i class="fas fa-plus-circle text-gray-600"></i>
                    <span class="text-xs mt-1">Add</span>
                </a>
                <a href="" class="flex flex-col items-center">
                    <i class="fas fa-chart-bar text-gray-600"></i>
                    <span class="text-xs mt-1">Reports</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center">
                    <i class="fas fa-user text-gray-600"></i>
                    <span class="text-xs mt-1">Profile</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 md:ml-0">
            <!-- Top Navigation -->
            <header class="bg-white border-b border-gray-200">
                <div class="px-4 py-3 md:px-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="md:hidden text-gray-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h1 class="text-xl font-semibold text-gray-800">@yield('header-title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-500">@yield('header-subtitle', 'Overview of your finances')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:text-blue-600">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <!-- Quick Add -->
                        <div class="relative">
                            <button id="quickAddBtn" class="bg-gradient-to-br from-blue-600 to-violet-600 text-white px-4 py-2 rounded-xl hover:shadow-lg transition">
                                <i class="fas fa-plus mr-2"></i>Quick Add
                            </button>
                            <!-- Quick Add Dropdown -->
                            <div id="quickAddMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                                <a href="" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-minus-circle text-red-500 mr-3"></i>
                                    <span>Add Expense</span>
                                </a>
                                <a href="" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-plus-circle text-green-500 mr-3"></i>
                                    <span>Add Income</span>
                                </a>
                                <a href="" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-3"></i>
                                    <span>Add Activity</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

    @stack('scripts')
    
    <script>
        // Toggle Mobile Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('fixed');
            sidebar.classList.toggle('inset-y-0');
            sidebar.classList.toggle('left-0');
            sidebar.classList.toggle('z-50');
            sidebar.classList.toggle('bg-white');
            overlay.classList.toggle('hidden');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.add('hidden');
            this.classList.add('hidden');
        });

        // Quick Add Menu
        document.getElementById('quickAddBtn').addEventListener('click', function() {
            const menu = document.getElementById('quickAddMenu');
            menu.classList.toggle('hidden');
        });

        // Close quick add menu when clicking elsewhere
        document.addEventListener('click', function(event) {
            const quickAddBtn = document.getElementById('quickAddBtn');
            const quickAddMenu = document.getElementById('quickAddMenu');
            
            if (!quickAddBtn.contains(event.target) && !quickAddMenu.contains(event.target)) {
                quickAddMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html> --}} 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FinanceTrack') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .stat-card {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            transition: all 0.3s ease;
            border: 1px solid #333;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 107, 0, 0.15);
            border-color: #ff6b00;
        }
        .active-nav {
            background: linear-gradient(90deg, rgba(255, 107, 0, 0.2) 0%, transparent 100%);
            border-left: 4px solid #ff6b00;
            color: #ff6b00 !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #ff6b00 0%, #ff8c42 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 0, 0.3);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #ff6b00 0%, #ff8c42 100%);
        }
        .text-orange {
            color: #ff6b00;
        }
        .bg-orange {
            background-color: #ff6b00;
        }
        .border-orange {
            border-color: #ff6b00;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-gray-800 border-r border-gray-700 hidden md:block">
            <div class="p-6">
                <!-- Logo -->
                <div class="flex items-center space-x-3 mb-8">
                    <div class="gradient-bg w-10 h-10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-white">FinanceHub</span>
                </div>

                <!-- User Profile -->
                <div class="flex items-center space-x-3 mb-8 p-3 bg-gray-700 rounded-xl">
                    <div class="w-10 h-10 gradient-bg rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-white">{{ auth()->user()->name }}</h3>
                        <p class="text-xs text-gray-400">{{ auth()->user()->job ?: 'Set your job' }}</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange rounded-xl transition {{ request()->routeIs('dashboard') ? 'active-nav' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange rounded-xl transition">
                        <i class="fas fa-receipt w-5"></i>
                        <span>Expenses</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange rounded-xl transition">
                        <i class="fas fa-money-bill-wave w-5"></i>
                        <span>Income</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange rounded-xl transition">
                        <i class="fas fa-map-marker-alt w-5"></i>
                        <span>Activities</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange rounded-xl transition">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Reports</span>
                    </a>
                    <a href="" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange rounded-xl transition">
                        <i class="fas fa-piggy-bank w-5"></i>
                        <span>Budgets</span>
                    </a>
                    <div class="pt-4 mt-4 border-t border-gray-700">
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange rounded-xl transition">
                            <i class="fas fa-user-cog w-5"></i>
                            <span>Settings</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 w-full px-4 py-3 text-gray-300 hover:bg-red-900 hover:text-red-400 rounded-xl transition">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Mobile Sidebar -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-gray-800 border-t border-gray-700 z-50">
            <div class="flex justify-around py-3">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-gray-300 hover:text-orange transition">
                    <i class="fas fa-home"></i>
                    <span class="text-xs mt-1">Home</span>
                </a>
                <a href="" class="flex flex-col items-center text-gray-300 hover:text-orange transition">
                    <i class="fas fa-receipt"></i>
                    <span class="text-xs mt-1">Expenses</span>
                </a>
                <a href="" class="flex flex-col items-center text-gray-300 hover:text-orange transition">
                    <i class="fas fa-plus-circle"></i>
                    <span class="text-xs mt-1">Add</span>
                </a>
                <a href="" class="flex flex-col items-center text-gray-300 hover:text-orange transition">
                    <i class="fas fa-chart-bar"></i>
                    <span class="text-xs mt-1">Reports</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center text-gray-300 hover:text-orange transition">
                    <i class="fas fa-user"></i>
                    <span class="text-xs mt-1">Profile</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 md:ml-0">
            <!-- Top Navigation -->
            <header class="bg-gray-800 border-b border-gray-700">
                <div class="px-4 py-3 md:px-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="md:hidden text-gray-300 hover:text-orange">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h1 class="text-xl font-semibold text-white">@yield('header-title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-400">@yield('header-subtitle', 'Overview of your finances')</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-300 hover:text-orange">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <!-- Quick Add -->
                        <div class="relative">
                            <button id="quickAddBtn" class="btn-primary text-white px-4 py-2 rounded-xl">
                                <i class="fas fa-plus mr-2"></i>Quick Add
                            </button>
                            <!-- Quick Add Dropdown -->
                            <div id="quickAddMenu" class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-xl shadow-lg border border-gray-700 py-2 z-50">
                                <a href="" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange transition">
                                    <i class="fas fa-minus-circle text-red-400 mr-3"></i>
                                    <span>Add Expense</span>
                                </a>
                                <a href="" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange transition">
                                    <i class="fas fa-plus-circle text-green-400 mr-3"></i>
                                    <span>Add Income</span>
                                </a>
                                <a href="" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-orange transition">
                                    <i class="fas fa-map-marker-alt text-blue-400 mr-3"></i>
                                    <span>Add Activity</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4 md:p-6">
                {{ $slot }}
                {{-- @yield('content') --}}
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-70 z-40 hidden"></div>

    @stack('scripts')
    
    <script>
        // Toggle Mobile Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('fixed');
            sidebar.classList.toggle('inset-y-0');
            sidebar.classList.toggle('left-0');
            sidebar.classList.toggle('z-50');
            sidebar.classList.toggle('bg-gray-800');
            overlay.classList.toggle('hidden');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.add('hidden');
            this.classList.add('hidden');
        });

        // Quick Add Menu
        document.getElementById('quickAddBtn').addEventListener('click', function() {
            const menu = document.getElementById('quickAddMenu');
            menu.classList.toggle('hidden');
        });

        // Close quick add menu when clicking elsewhere
        document.addEventListener('click', function(event) {
            const quickAddBtn = document.getElementById('quickAddBtn');
            const quickAddMenu = document.getElementById('quickAddMenu');
            
            if (!quickAddBtn.contains(event.target) && !quickAddMenu.contains(event.target)) {
                quickAddMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>