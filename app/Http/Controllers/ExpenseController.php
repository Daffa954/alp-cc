<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('dashboard.expense', [
            'expenses' => Expense::latest()->get(),
            'activities' => Activity::all()
        ]);
    }

    public function store(Request $r)
    {
        Expense::create($r->all());
        return back();
    }

    public function delete($id)
    {
        Expense::findOrFail($id)->delete();
        return back();
    }
}
