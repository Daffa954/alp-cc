<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {
        $biggestCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        $tips = null;

        if ($biggestCategory) {
            if ($biggestCategory->category === 'Food')
                $tips = "You spent too much on Food. Try meal prepping 3x per week.";
            else if ($biggestCategory->category === 'Transport')
                $tips = "Transport cost is high. Consider using public transport occasionally.";
            else
                $tips = "Review your spending in " . $biggestCategory->category . ".";
        }

        return view('dashboard.recommendation', [
            'biggestCategory' => $biggestCategory,
            'tips' => $tips
        ]);
    }
}
