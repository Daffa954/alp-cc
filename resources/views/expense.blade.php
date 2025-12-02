@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-rose-400 mb-4 transition-colors group">
            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
        <h1 class="text-3xl font-black text-white">Add New Expense</h1>
        <p class="text-slate-400">Track where your money is going.</p>
    </div>

    <!-- Glass Card -->
    <div class="bg-slate-900/60 backdrop-blur-xl border border-rose-500/20 rounded-3xl p-8 relative overflow-hidden shadow-2xl shadow-rose-500/10">
        
        <!-- Top Glow Accent -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-600 via-pink-500 to-rose-600"></div>

        <form action="{{ url('/expense') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Grid for Category and Amount -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Category Input -->
                <div class="group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-rose-400 transition-colors">
                        Category
                    </label>
                    <div class="relative">
                        <select name="category" 
                            class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>Select Category</option>
                            <option value="Food">Food & Dining</option>
                            <option value="Transport">Transportation</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Shopping">Shopping</option>
                            <option value="Others">Others</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Amount Input -->
                <div class="group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-rose-400 transition-colors">
                        Amount
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-500">Rp</span>
                        </div>
                        <input type="number" name="amount" step="0.01"
                            class="w-full bg-slate-800/50 border border-slate-700 rounded-xl pl-12 pr-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all font-mono"
                            placeholder="0.00" required>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="group">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-rose-400 transition-colors">
                    Description
                </label>
                <input type="text" name="description" 
                    class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all"
                    placeholder="What did you buy?" required>
            </div>

            <!-- Date and Activity (Optional) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date Spent (maps to data_spent) -->
                <div class="group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-rose-400 transition-colors">
                        Date Spent
                    </label>
                    <input type="date" name="data_spent" 
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all [color-scheme:dark]"
                        value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Activity (Foreign Key Placeholder) -->
                <div class="group">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-rose-400 transition-colors">
                        Related Activity (Optional)
                    </label>
                    <input type="text" name="activity_id" 
                        class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all"
                        placeholder="Link to an activity ID">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-500 hover:to-pink-500 text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-rose-500/20 transform transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Save Expense
                </button>
            </div>

        </form>
    </div>
</div>
@endsection