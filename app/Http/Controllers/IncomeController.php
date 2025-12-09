<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
        // Tampilkan pemasukan user, urutkan dari tanggal terbaru
        $incomes = Income::where('user_id', Auth::id())
            ->orderBy('date_received', 'desc')
            ->paginate(10);

        return view('incomes.index', compact('incomes'));
    }

    public function create()
    {
        return view('incomes.create');
    }

    public function store(Request $request)
    {
        // 1. Bersihkan format ribuan (titik) pada amount
        if ($request->has('amount')) {
            $request->merge([
                'amount' => str_replace('.', '', $request->amount)
            ]);
        }

        // 2. Validasi
        $request->validate([
            'source' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date_received' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // 3. Simpan
        Income::create([
            'user_id' => Auth::id(),
            'source' => $request->source,
            'amount' => $request->amount,
            'date_received' => $request->date_received,
            'notes' => $request->notes,
        ]);

        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil dicatat!');
    }

    public function edit(Income $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }
        return view('incomes.edit', compact('income'));
    }

    public function update(Request $request, Income $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }

        // 1. Bersihkan format ribuan
        if ($request->has('amount')) {
            $request->merge([
                'amount' => str_replace('.', '', $request->amount)
            ]);
        }

        // 2. Validasi
        $request->validate([
            'source' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date_received' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // 3. Update
        $income->update($request->all());

        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil diperbarui!');
    }

    public function destroy(Income $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403);
        }

        $income->delete();

        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil dihapus.');
    }
}