<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get expenses with pagination
        $expenses = Expense::where('user_id', $user->id)
            ->with('activity')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Get summary data
        $totalExpense = Expense::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        $averageDaily = Expense::where('user_id', $user->id)
            ->whereDate('created_at', now())
            ->avg('amount') ?? 0;
        
        // Category breakdown for current month
        $categories = Expense::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();
        
        return view('expenseIndex', compact('expenses', 'totalExpense', 'averageDaily', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get recent activities for dropdown
        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('date_start', 'desc')
            ->limit(10)
            ->get();
        
        // Predefined categories
        $categories = [
            'food' => 'Food & Dining',
            'transportation' => 'Transportation',
            'shopping' => 'Shopping',
            'entertainment' => 'Entertainment',
            'housing' => 'Housing & Utilities',
            'health' => 'Health & Medical',
            'education' => 'Education',
            'personal' => 'Personal Care',
            'travel' => 'Travel',
            'gifts' => 'Gifts & Donations',
            'other' => 'Other'
        ];
        
        return view('expenseCreate', compact('recentActivities', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('amount')) {
        $request->merge([
            'amount' => str_replace('.', '', $request->input('amount'))
        ]);
    }
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'activity_id' => 'nullable|exists:activities,id',
            'date' => 'required|date',
        ]);
        
        $expense = Expense::create([
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'activity_id' => $validated['activity_id'],
            'date' => $validated['date'],
        ]);
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense added successfully!');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Expense $expense)
    // {
    //     $this->authorize('view', $expense);
        
    //     return view('expenses.show', compact('expense'));
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Expense $expense)
    // {
    //     $this->authorize('update', $expense);
        
    //     $user = Auth::user();
    //     $recentActivities = Activity::where('user_id', $user->id)
    //         ->orderBy('date_start', 'desc')
    //         ->limit(10)
    //         ->get();
        
    //     $categories = [
    //         'food' => 'Food & Dining',
    //         'transportation' => 'Transportation',
    //         'shopping' => 'Shopping',
    //         'entertainment' => 'Entertainment',
    //         'housing' => 'Housing & Utilities',
    //         'health' => 'Health & Medical',
    //         'education' => 'Education',
    //         'personal' => 'Personal Care',
    //         'travel' => 'Travel',
    //         'gifts' => 'Gifts & Donations',
    //         'other' => 'Other'
    //     ];
        
    //     return view('expenses.edit', compact('expense', 'recentActivities', 'categories'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Expense $expense)
    // {
    //     $this->authorize('update', $expense);
        
    //     $validated = $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'category' => 'required|string|max:100',
    //         'description' => 'nullable|string|max:500',
    //         'activity_id' => 'nullable|exists:activities,id',
    //         'date' => 'required|date',
    //     ]);
        
    //     $expense->update($validated);
        
    //     return redirect()->route('expenses.index')
    //         ->with('success', 'Expense updated successfully!');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Expense $expense)
    // {
    //     $this->authorize('delete', $expense);
        
    //     $expense->delete();
        
    //     return redirect()->route('expenses.index')
    //         ->with('success', 'Expense deleted successfully!');
    // }
    
    /**
     * Quick add expense from dashboard
     */
    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:200',
        ]);
        
        Expense::create([
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'date' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Expense added quickly!');
    }
}
