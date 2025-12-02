@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-emerald-400 mb-4 transition-colors group">
            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
        <h1 class="text-3xl font-black text-white">Record Income</h1>
        <p class="text-slate-400">Add a new earning source to your wallet.</p>
    </div>

    <!-- Glass Card -->
    <div class="bg-slate-900/60 backdrop-blur-xl border border-emerald-500/20 rounded-3xl p-8 relative overflow-hidden shadow-2xl shadow-emerald-500/10">
        
        <!-- Top Glow Accent -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-600"></div>

        <form action="{{ url('/income') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Grid for Source and Amount -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Source Input -->
                <div class="group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-emerald-400 transition-colors">
                        Source
                    </label>
                    <div class="relative">
                        <input type="text" name="source" 
                            class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all"
                            placeholder="e.g. Salary, Freelance Project" required>
                    </div>
                </div>

                <!-- Amount Input -->
                <div class="group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-emerald-400 transition-colors">
                        Amount
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-500 ">Rp</span>
                        </div>
                        
                        <!-- Changed "pl-8" to "pl-12" to make room for the wider text -->
                        <input type="number" name="amount" step="0.01"
                            class="w-full bg-slate-800/50 border border-slate-700 rounded-xl pl-12 pr-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-mono"
                            placeholder="0.00" required>
                    </div>
                </div>
            </div>

            <!-- Date Received -->
            <div class="group">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-emerald-400 transition-colors">
                    Date Received
                </label>
                <input type="date" name="date_received" 
                    class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all [color-scheme:dark]"
                    value="{{ date('Y-m-d') }}" required>
            </div>

            <!-- Notes -->
            <div class="group">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-emerald-400 transition-colors">
                    Notes (Optional)
                </label>
                <textarea name="notes" rows="3" 
                    class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all resize-none"
                    placeholder="Additional details about this income..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-emerald-500/20 transform transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Save Income
                </button>
            </div>

        </form>
    </div>
</div>
@endsection