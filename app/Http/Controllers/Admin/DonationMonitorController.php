<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationMonitorController extends Controller
{
    public function index(Request $request)
{
    $q = Donation::with(['panti', 'need', 'user']);

    if ($request->filled('search')) {
        $s = "%{$request->search}%";
        $q->where(fn ($w) => $w->where('donor_name', 'like', $s)->orWhereHas('panti', fn ($p) => $p->where('name', 'like', $s)));
    }
    if ($request->filled('status')) $q->where('status', $request->status);

    return view('dashboard.admin.donations.index', ['donations' => $q->latest()->paginate(15)->withQueryString()]);
}
}
