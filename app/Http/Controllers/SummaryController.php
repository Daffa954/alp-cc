<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index()
    {
        $totalIncome = Income::sum('amount');
        $totalExpense = Expense::sum('amount');
        $avgExpense = Expense::avg('amount');

        return view('dashboard.summary', compact(
            'totalIncome',
            'totalExpense',
            'avgExpense',
        ));
    }
}
