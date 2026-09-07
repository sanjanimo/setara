<?php

namespace App\Http\Controllers\Relawan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Relawan\StoreVolunteerApplicationRequest;
use App\Http\Requests\Relawan\StoreVisitReportRequest;
use App\Models\ActivityLog;
use App\Models\Module;
use App\Models\Panti;
use App\Models\VolunteerApplication;
use Illuminate\Support\Facades\Auth;


class VolunteerController extends Controller
{
    public function search()
    {
        $pantis = Panti::verified()->with('youthProfiles')->get();
        return view('dashboard.relawan.applications.search', compact('pantis'));
    }

    public function create(Panti $panti)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($panti->isVerified(), 404);

        $requiredTargets = $this->requiredTargets($panti);
        $canApply = $this->canApply($user, $requiredTargets);
        $youths = $panti->youthProfiles()->where('mentor_needed', true)->where('status', 'baru')->get();

        return view('dashboard.relawan.applications.create', compact('panti', 'canApply', 'youths', 'requiredTargets'));
    }

    public function store(Panti $panti, StoreVolunteerApplicationRequest $request)
    {
        abort_unless($panti->isVerified(), 404);
        abort_unless($this->canApply(Auth::user(), $this->requiredTargets($panti)), 403, 'Selesaikan modul pembekalan yang diwajibkan sebelum mengajukan kegiatan.');

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = VolunteerApplication::STATUS_DIAJUKAN;

        $application = $panti->volunteerApplications()->create($data);

        ActivityLog::record('volunteer.submitted', "Relawan " . Auth::user()->name . " mengajukan {$data['activity_type']} ke {$panti->name}.", $application);

        return redirect()->action([self::class, 'index'])->with('success', 'Pengajuan kunjungan berhasil dikirim.');
    }

    private function requiredTargets(Panti $panti): array
    {
        return match ($panti->type) {
            Panti::TYPE_ANAK => ['umum', 'anak'],
            Panti::TYPE_JOMPO => ['umum', 'lansia'],
            Panti::TYPE_CAMPURAN => ['umum', 'anak', 'lansia'],
            default => ['umum'],
        };
    }

    private function canApply($user, array $requiredTargets): bool
    {
        $requiredModuleIds = Module::whereIn('target', $requiredTargets)
            ->where('level', 'basic')
            ->pluck('id');

        return $user->moduleAttempts()
            ->whereIn('module_id', $requiredModuleIds)
            ->where('passed', true)
            ->count() >= $requiredModuleIds->count();
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $applications = $user->volunteerApplications()->with(['panti', 'youthProfile', 'visitReport'])->latest()->get();
        return view('dashboard.relawan.applications.index', compact('applications'));
    }

    public function createReport(VolunteerApplication $application)
    {
        abort_unless($application->user_id === Auth::id(), 403);
        abort_unless($application->status === VolunteerApplication::STATUS_DISETUJUI, 403, 'Pengajuan belum disetujui.');
        abort_unless(! $application->visitReport, 403, 'Laporan sudah dibuat.');

        return view('dashboard.relawan.reports.create', compact('application'));
    }

    public function storeReport(VolunteerApplication $application, StoreVisitReportRequest $request)
    {
        abort_unless($application->user_id === Auth::id(), 403);

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['panti_id'] = $application->panti_id;

        $report = $application->visitReport()->create($data);
        $application->update(['status' => VolunteerApplication::STATUS_SELESAI]);

        ActivityLog::record('visit_report.created', "Relawan " . Auth::user()->name . " membuat laporan kunjungan ke {$application->panti->name}.", $report);

        return redirect()->action([self::class, 'index'])->with('success', 'Laporan kunjungan berhasil dikirim.');
    }
}
