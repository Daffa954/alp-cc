@extends('layouts.auth')

@section('content')
<div class="bg-slate-900/60 backdrop-blur-xl border border-white/10 rounded-3xl p-8 md:p-10 shadow-2xl relative overflow-hidden w-full max-w-md mx-auto">
    
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-cyan-500"></div>
    
    <div class="absolute top-10 right-10 w-2 h-2 bg-cyan-400 rounded-full opacity-60 animate-ping"></div>
    <div class="absolute bottom-20 left-10 w-2 h-2 bg-purple-400 rounded-full opacity-60 animate-ping delay-1000"></div>
    
    <div class="text-center mb-10 relative">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-600 mb-6 shadow-lg shadow-blue-500/30 relative group">
            <div class="absolute inset-0 rounded-2xl bg-white/20 blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <svg class="w-10 h-10 text-white relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-purple-400 to-cyan-400 mb-2 tracking-tight">
            Welcome Back
        </h2>
        <p class="text-slate-400 text-sm">
            Access your <span class="text-cyan-400 font-semibold">financial command center</span>
        </p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="group">
            <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-cyan-400 transition-colors">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <div class="w-8 h-8 rounded-lg bg-slate-800/50 flex items-center justify-center border border-slate-700/50 group-focus-within:border-cyan-500/50 transition-all">
                        <svg class="h-4 w-4 text-slate-400 group-focus-within:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                </div>
                <input type="email" name="email" id="email" 
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-14 pr-4 py-3.5 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all duration-300"
                    placeholder="your@email.com">
            </div>
        </div>

        <div class="group">
            <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-cyan-400 transition-colors">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <div class="w-8 h-8 rounded-lg bg-slate-800/50 flex items-center justify-center border border-slate-700/50 group-focus-within:border-cyan-500/50 transition-all">
                        <svg class="h-4 w-4 text-slate-400 group-focus-within:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
                <input type="password" name="password" id="password" 
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-14 pr-4 py-3.5 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all duration-300"
                    placeholder="••••••••••">
            </div>
            
            <div class="flex justify-end mt-3">
                <a href="#" class="text-xs text-slate-400 hover:text-cyan-400 transition-colors font-semibold hover:underline">
                    Forgot password?
                </a>
            </div>
        </div>

        <button type="submit" 
            class="w-full bg-gradient-to-r from-cyan-600 via-blue-600 to-purple-600 hover:from-cyan-500 hover:via-blue-500 hover:to-purple-500 text-white font-bold py-4 px-4 rounded-xl shadow-lg shadow-blue-500/30 transform transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] relative overflow-hidden group">
            <span class="relative z-10 flex items-center justify-center gap-2">
                <span>Sign In</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out"></div>
        </button>

        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 py-1 bg-slate-900/80 text-slate-500 text-xs rounded-full border border-slate-800 backdrop-blur-sm">
                    New to Finance Tracker?
                </span>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}" 
                class="inline-flex items-center justify-center gap-2 text-sm text-slate-300 hover:text-white transition-all font-bold group">
                <span class="group-hover:text-cyan-400 transition-colors">Create your account</span>
                <svg class="w-4 h-4 text-slate-500 group-hover:text-cyan-400 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </form>
    
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-500/10 rounded-tr-full blur-2xl pointer-events-none"></div>
    <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-bl-full blur-2xl pointer-events-none"></div>
</div>
@endsection