@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header Section -->
    <div class="text-center md:text-left md:flex md:items-end md:justify-between mb-8">
        <div>
            <h1 class="text-4xl font-black text-white mb-2 tracking-tight">Dashboard</h1>
            <p class="text-slate-400">Manage your weekly budget and track activity.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                <span class="w-2 h-2 rounded-full bg-cyan-400 mr-2 animate-pulse"></span>
                System Active
            </span>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Add Income Button -->
        {{-- {{ route('income.create') }} --}}
        <a href="{{ route('income.create') }}" class="group relative overflow-hidden bg-slate-800/50 hover:bg-slate-800/80 border border-slate-700/50 hover:border-emerald-500/50 rounded-3xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/20">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-bl-full blur-2xl transition-all group-hover:bg-emerald-500/20"></div>
            
            <div class="relative z-10 flex flex-col items-center justify-center text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white group-hover:text-emerald-400 transition-colors">Add Income</h3>
                    <p class="text-slate-400 text-sm mt-1">Record earnings or salary</p>
                </div>
            </div>
            
            <!-- Arrow hint -->
            <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </a>

        <!-- Add Expense Button -->
        {{-- {{ route('expense.create') }} --}}
        <a href="{{ route('expense.create') }}" class="group relative overflow-hidden bg-slate-800/50 hover:bg-slate-800/80 border border-slate-700/50 hover:border-rose-500/50 rounded-3xl p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-rose-500/20">
            <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/10 rounded-bl-full blur-2xl transition-all group-hover:bg-rose-500/20"></div>
            
            <div class="relative z-10 flex flex-col items-center justify-center text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white group-hover:text-rose-400 transition-colors">Add Expense</h3>
                    <p class="text-slate-400 text-sm mt-1">Track daily spending</p>
                </div>
            </div>

            <!-- Arrow hint -->
            <div class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                <svg class="w-6 h-6 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </a>

    </div>

    <!-- Placeholder for Future Content -->
    <div class="mt-12 p-8 border border-dashed border-slate-700 rounded-3xl bg-slate-900/30 text-center">
        <p class="text-slate-500 italic">Weekly summary charts will appear here...</p>
    </div>

</div>
@endsection