<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen font-sans text-slate-300 selection:bg-cyan-500 selection:text-white">

    <!-- 1. BACKGROUND GLOWS (Fixed) -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <!-- 2. NAVBAR (Glassmorphism) -->
    <nav class="fixed top-0 w-full z-50 border-b border-white/10 bg-slate-900/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight">Finance AI</span>
                </div>

                <!-- Right Side (User & Logout) -->
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-slate-400 hidden md:block">Hello, {{ Auth::user()->name ?? 'User' }}</span>
                    
                    <!-- Logout Form -->
                    {{-- {{ route('logout') }} --}}
                    <form method="POST" action="">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white uppercase tracking-wider bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all hover:border-red-500/50 hover:text-red-400 group flex items-center gap-2">
                            <span>Logout</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- 3. MAIN CONTENT -->
    <main class="relative z-10 pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        @yield('content')
    </main>

</body>
</html>