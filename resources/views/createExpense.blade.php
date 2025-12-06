<x-app-layout>
    <x-slot name="header-title">Add New Expense</x-slot>
    <x-slot name="header-subtitle">Record your spending</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-black rounded-2xl border border-darker-black p-6">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('expenses.index') }}" class="inline-flex items-center text-orange hover:text-orange-light">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Expenses
                </a>
            </div>

            <h2 class="text-xl font-semibold text-white mb-6">Add New Expense</h2>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl" style="background-color: rgba(255, 107, 0, 0.1); border-left: 4px solid #ff6b00;">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-orange mr-3"></i>
                        <span class="text-white">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6">
                @csrf

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-money-bill-wave text-orange mr-2"></i>Amount
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">Rp</span>
                        </div>
                        <input 
                            type="number" 
                            id="amount" 
                            name="amount" 
                            step="0.01"
                            min="0"
                            required
                            class="w-full pl-12 pr-4 py-3 bg-dark-black border border-darker-black rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-orange focus:ring-2 focus:ring-orange/20 transition"
                            placeholder="0.00"
                            value="{{ old('amount') }}"
                        >
                    </div>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-tag text-orange mr-2"></i>Category
                    </label>
                    <select 
                        id="category" 
                        name="category"
                        required
                        class="w-full px-4 py-3 bg-dark-black border border-darker-black rounded-xl text-white focus:outline-none focus:border-orange focus:ring-2 focus:ring-orange/20 transition"
                    >
                        <option value="" disabled selected>Select a category</option>
                        @foreach($categories as $key => $value)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-file-alt text-orange mr-2"></i>Description
                    </label>
                    <textarea 
                        id="description" 
                        name="description"
                        rows="3"
                        class="w-full px-4 py-3 bg-dark-black border border-darker-black rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-orange focus:ring-2 focus:ring-orange/20 transition"
                        placeholder="What was this expense for? (optional)"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-calendar text-orange mr-2"></i>Date
                    </label>
                    <input 
                        type="date" 
                        id="date" 
                        name="date"
                        required
                        class="w-full px-4 py-3 bg-dark-black border border-darker-black rounded-xl text-white focus:outline-none focus:border-orange focus:ring-2 focus:ring-orange/20 transition"
                        value="{{ old('date', date('Y-m-d')) }}"
                    >
                    @error('date')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Activity (Optional) -->
                <div>
                    <label for="activity_id" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-map-marker-alt text-orange mr-2"></i>Link to Activity (Optional)
                    </label>
                    <select 
                        id="activity_id" 
                        name="activity_id"
                        class="w-full px-4 py-3 bg-dark-black border border-darker-black rounded-xl text-white focus:outline-none focus:border-orange focus:ring-2 focus:ring-orange/20 transition"
                    >
                        <option value="">No activity linked</option>
                        @foreach($recentActivities as $activity)
                            <option value="{{ $activity->id }}" {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                                {{ $activity->title }} - {{ $activity->date_start->format('M d, Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('activity_id')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-darker-black">
                    <button type="button" onclick="window.location.href='{{ route('expenses.index') }}'" 
                        class="px-6 py-3 border border-darker-black rounded-xl text-gray-400 hover:text-white hover:border-gray-600 transition">
                        Cancel
                    </button>
                    
                    <button type="submit" 
                        class="px-6 py-3 bg-orange text-white font-semibold rounded-xl hover:bg-orange-light hover:shadow-lg transition">
                        <i class="fas fa-save mr-2"></i>Save Expense
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Tips -->
        <div class="mt-6 bg-black rounded-2xl border border-darker-black p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <i class="fas fa-lightbulb text-orange mr-2"></i> Quick Tips
            </h3>
            <ul class="space-y-3 text-gray-400">
                <li class="flex items-start">
                    <i class="fas fa-check text-orange mr-2 mt-1"></i>
                    <span>Be specific with descriptions for better tracking</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-orange mr-2 mt-1"></i>
                    <span>Link expenses to activities for better context</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-orange mr-2 mt-1"></i>
                    <span>Record expenses as soon as they occur for accuracy</span>
                </li>
            </ul>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-format currency input
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d.]/g, '');
            if (value) {
                // Format with thousand separators
                let parts = value.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                e.target.value = parts.join('.');
            }
        });

        // Set max date to today
        document.getElementById('date').max = new Date().toISOString().split("T")[0];
    </script>
    @endpush
</x-app-layout>