<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    // public function index()
    // {
    //     // Ambil data milik user yang sedang login, urutkan dari yang terbaru
    //     $activities = Activity::where('user_id', Auth::id())
    //         ->orderBy('date_start', 'desc')
    //         ->paginate(10);

    //     return view('activities.index', compact('activities'));
    // }
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Activity::where('user_id', $user->id);

        if ($request->has('filter_date')) {
            $query->whereDate('date_start', '<=', $request->filter_date);
        }

        $activities = $query->orderBy('date_start', 'desc')->paginate(5);

        // Month Stats
        $totalCost = Activity::where('user_id', $user->id)
            ->whereMonth('date_start', now()->month)
            ->sum('cost_to_there');

        $totalKm = Activity::where('user_id', $user->id)
            ->whereMonth('date_start', now()->month)
            ->sum('distance_in_km');

        $popularTransport = Activity::where('user_id', $user->id)
            ->whereMonth('date_start', now()->month)
            ->selectRaw('transportation, COUNT(*) as count')
            ->groupBy('transportation')
            ->orderByDesc('count')
            ->first();

        return view('activities.index', compact('activities', 'totalCost', 'totalKm', 'popularTransport'));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        // 1. Bersihkan format ribuan (titik) pada cost
        if ($request->has('cost_to_there')) {
            $request->merge([
                'cost_to_there' => str_replace('.', '', $request->cost_to_there)
            ]);
        }

        // 2. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'cost_to_there' => 'nullable|numeric|min:0',
            'distance_in_km' => 'nullable|numeric|min:0',
            // Validasi lain opsional
        ]);

        // 3. Simpan
        $data = $request->all();
        $data['user_id'] = Auth::id();

        Activity::create($data);

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    public function edit(Activity $activity)
    {
        // Pastikan hanya pemilik yang bisa edit
        if ($activity->user_id !== Auth::id()) {
            abort(403);
        }
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        if ($activity->user_id !== Auth::id()) {
            abort(403);
        }

        // 1. Bersihkan format ribuan
        if ($request->has('cost_to_there')) {
            $request->merge([
                'cost_to_there' => str_replace('.', '', $request->cost_to_there)
            ]);
        }

        // 2. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
            'cost_to_there' => 'nullable|numeric|min:0',
        ]);

        // 3. Update
        $activity->update($request->all());

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil diperbarui!');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->user_id !== Auth::id()) {
            abort(403);
        }

        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil dihapus.');
    }
}