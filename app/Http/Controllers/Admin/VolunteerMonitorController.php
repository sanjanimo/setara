<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;

class VolunteerMonitorController extends Controller
{
public function index(Request $request)
{
    $q = VolunteerApplication::with(['panti', 'user']);

    if ($request->filled('search')) {
        $s = "%{$request->search}%";
        $q->where(fn ($w) => $w->whereHas('user', fn ($u) => $u->where('name', 'like', $s))->orWhereHas('panti', fn ($p) => $p->where('name', 'like', $s)));
    }
    if ($request->filled('status')) $q->where('status', $request->status);

    return view('dashboard.admin.volunteers.index', ['applications' => $q->latest()->paginate(15)->withQueryString()]);
}
}
