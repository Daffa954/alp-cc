<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        return view('dashboard.activity', [
            'activities' => Activity::latest()->get()
        ]);
    }

    public function store(Request $r)
    {
        Activity::create($r->all());
        return back();
    }

    public function delete($id)
    {
        Activity::findOrFail($id)->delete();
        return back();
    }
}
