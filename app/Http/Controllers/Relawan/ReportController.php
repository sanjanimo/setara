<?php

namespace App\Http\Controllers\Relawan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $reports = $user->visitReports()->with('panti')->latest()->get();

        return view('dashboard.relawan.reports.index', compact('reports'));
    }
}
