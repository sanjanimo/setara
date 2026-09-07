<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\VolunteerApplication;
use Illuminate\Support\Facades\Auth;

class VolunteerManagementController extends Controller
{
    public function index()
    {
        $panti = Auth::user()->panti;
        abort_unless($panti, 404);

        $applications = $panti->volunteerApplications()->with(['user', 'youthProfile'])->latest()->get();

        return view('dashboard.panti.volunteers.index', compact('panti', 'applications'));
    }

    public function approve(VolunteerApplication $application)
    {
        $this->authorize($application);
        abort_unless($application->status === VolunteerApplication::STATUS_DIAJUKAN, 403);

        $application->update([
            'status' => VolunteerApplication::STATUS_DISETUJUI,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        ActivityLog::record('volunteer.approved', "Panti {$application->panti->name} menyetujui pengajuan dari {$application->user->name}.", $application);
        return back()->with('success', 'Pengajuan relawan disetujui.');
    }

    public function reject(VolunteerApplication $application)
    {
        $this->authorize($application);
        abort_unless($application->status === VolunteerApplication::STATUS_DIAJUKAN, 403);

        $application->update([
            'status' => VolunteerApplication::STATUS_DITOLAK,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'note' => 'Mohon maaf, saat ini kami belum dapat menerima pengajuan ini.',
        ]);

        ActivityLog::record('volunteer.rejected', "Panti {$application->panti->name} belum dapat menerima pengajuan dari {$application->user->name}.", $application);
        return back()->with('success', 'Pengajuan relawan ditandai belum dapat diterima.');
    }

    protected function authorize(VolunteerApplication $application): void
    {
        $panti = Auth::user()->panti;
        abort_unless($panti && $application->panti_id === $panti->id, 403);
    }
}
