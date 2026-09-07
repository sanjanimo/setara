<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $panti = Auth::user()->panti;
        abort_unless($panti, 404, 'Profil panti belum dibuat.');

        $reports = $panti->visitReports()->with('user')->latest()->get();

        return view('dashboard.panti.reports.index', compact('panti', 'reports'));
    }
}
