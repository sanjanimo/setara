<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
public function index(Request $request)
{
    $q = ActivityLog::with('user');

    if ($request->filled('search')) {
        $s = "%{$request->search}%";
        $q->where(fn ($w) => $w->where('action', 'like', $s)->orWhere('description', 'like', $s));
    }

    return view('dashboard.admin.logs.index', ['logs' => $q->latest()->paginate(20)->withQueryString()]);
}
}
