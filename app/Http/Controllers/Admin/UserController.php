<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
{
    $q = User::query();

    if ($request->filled('search')) {
        $s = "%{$request->search}%";
        $q->where(fn ($w) => $w->where('name', 'like', $s)->orWhere('email', 'like', $s));
    }
    if ($request->filled('role')) $q->where('role', $request->role);

    return view('dashboard.admin.users.index', ['users' => $q->orderByDesc('created_at')->paginate(15)->withQueryString()]);
}

    public function show(User $user)
    {
        $user->load([
            'donations.panti',
            'volunteerApplications.panti',
            'moduleAttempts.module',
            'visitReports.panti',
        ]);

        return view('dashboard.admin.users.show', compact('user'));
    }

    public function toggleActive(User $user)
    {
        abort_if($user->id === Auth::id(), 403, 'Kamu tidak dapat menonaktifkan akun sendiri.');

        $user->update(['is_active' => ! $user->is_active]);

        ActivityLog::record(
            $user->is_active ? 'user.activated' : 'user.deactivated',
            'Admin ' . ($user->is_active ? 'mengaktifkan' : 'menonaktifkan') . " akun {$user->name}.",
            $user
        );

        return back()->with('success', "Status akun {$user->name} diperbarui.");
    }
}
