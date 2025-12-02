@extends('layouts.auth')

@section('content')
<div class="bg-slate-900/60 backdrop-blur-xl border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
    
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500"></div>
    
    <div class="text-center mb-6 relative">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 mb-3 shadow-lg shadow-cyan-500/20">
            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </div>
        <h2 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 mb-1 tracking-tight">
            Create Account
        </h2>
        <p class="text-slate-400 text-sm">
            Start your financial journey with AI-powered insights
        </p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="space-y-5">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="group">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-focus-within:text-cyan-400 transition-colors">
                    Full Name
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" name="name" 
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all duration-300 text-sm" 
                        placeholder="John Doe">
                </div>
            </div>
            
            <div class="group">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-focus-within:text-cyan-400 transition-colors">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input type="email" name="email" 
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all duration-300 text-sm" 
                        placeholder="john@example.com">
                </div>
            </div>
        </div>

        <div class="group">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-focus-within:text-cyan-400 transition-colors">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500 group-focus-within:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input type="password" name="password" 
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all duration-300 text-sm" 
                    placeholder="Create a strong password">
            </div>
        </div>

        <div class="relative my-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-700/50"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="px-3 bg-slate-900/80 text-[10px] font-bold text-cyan-400 uppercase tracking-widest rounded-full backdrop-blur-sm border border-slate-700">
                    Profile Context
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="group">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-focus-within:text-blue-400 transition-colors">
                    Current Job
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="text" name="job" 
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all duration-300 text-sm" 
                        placeholder="e.g. Student">
                </div>
            </div>

            <div class="group">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-focus-within:text-blue-400 transition-colors">
                    Work Location
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <input type="text" name="job_location" 
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all duration-300 text-sm" 
                        placeholder="e.g. Campus">
                </div>
            </div>
        </div>

        <div class="group">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-focus-within:text-blue-400 transition-colors">
                Home Address
            </label>
            <div class="relative">
                <div class="absolute top-3 left-0 pl-4 flex items-start pointer-events-none">
                    <svg class="h-5 w-5 text-slate-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <textarea name="address" rows="2" 
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-11 pr-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all duration-300 resize-none text-sm" 
                    placeholder="Where do you commute from?"></textarea>
            </div>
        </div>

        <button type="submit" 
            class="w-full bg-gradient-to-r from-cyan-600 via-blue-600 to-purple-600 hover:from-cyan-500 hover:via-blue-500 hover:to-purple-500 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-cyan-500/50 transform transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] relative overflow-hidden group">
            <span class="relative z-10 flex items-center justify-center gap-2">
                <span>Create Account</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-blue-600 to-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </button>

        <div class="text-center">
            <span class="text-slate-400 text-xs">Already have an account? </span>
            <a href="{{ route('login') }}" 
                class="text-xs text-cyan-400 hover:text-blue-400 transition-colors font-bold inline-flex items-center gap-1 group">
                <span>Sign In</span>
                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </form>
</div>
@endsection