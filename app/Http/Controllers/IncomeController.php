<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    // public function index()
    // {
    //     // Tampilkan pemasukan user, urutkan dari tanggal terbaru
    //     $incomes = Income::where('user_id', Auth::id())
    //         ->orderBy('date_received', 'desc')
    //         ->paginate(10);

    //     return view('incomes.index', compact('incomes'));
    // }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Income::where('user_id', $user->id);

        // Filter Logic: Jump to date
        if ($request->has('filter_date')) {
            $query->whereDate('date_received', '<=', $request->filter_date);
        }

        $incomes = $query->orderBy('date_received', 'desc')->paginate(7);

        // Summary Statistics for current month
        $totalIncome = Income::where('user_id', $user->id)
            ->whereMonth('date_received', now()->month)
            ->sum('amount');

        $averageDaily = Income::where('user_id', $user->id)
            ->whereDate('date_received', now())
            ->avg('amount') ?? 0;

        // Source breakdown for current month
        $sources = Income::where('user_id', $user->id)
            ->whereMonth('date_received', now()->month)
            ->selectRaw('source, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        return view('incomes.index', compact('incomes', 'totalIncome', 'averageDaily', 'sources'));
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
            'is_regular' => 'nullable|boolean'
        ]);

        // 3. Simpan
        Income::create([
            'user_id' => Auth::id(),
            'source' => $request->source,
            'amount' => $request->amount,
            'date_received' => $request->date_received,
            'notes' => $request->notes,
            'is_regular' => $request->has('is_regular'),
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

    // app/Http/Controllers/IncomeController.php

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
    $validatedData = $request->validate([
        'source' => 'nullable|string|max:255',
        'amount' => 'required|numeric|min:0',
        'date_received' => 'required|date',
        'notes' => 'nullable|string',
        // 'is_regular' tidak perlu divalidasi disini karena kita ambil manual di bawah
    ]);

    // 3. Tambahkan logika boolean manual
    // INI PENTING: Karena HTML tidak mengirim value jika checkbox kosong (unchecked)
    $validatedData['is_regular'] = $request->boolean('is_regular');

    // 4. Update
    $income->update($validatedData);

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