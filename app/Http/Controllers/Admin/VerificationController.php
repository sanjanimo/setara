<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Panti;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Panti::with('user');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('city', 'like', $s)
                  ->orWhere('province', 'like', $s);
            });
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        $pantis = $query->orderByDesc('updated_at')->paginate(10)->withQueryString();

        return view('dashboard.admin.verification.index', compact('pantis'));
    }

    public function show(Panti $panti)
    {
        $panti->load(['user', 'needs.category']);

        return view('dashboard.admin.verification.show', compact('panti'));
    }

    public function approve(Panti $panti)
    {
        if (! $this->isCompleteForVerification($panti)) {
            return back()->with('error', 'Profil panti belum lengkap atau persetujuan publik belum diberikan.');
        }

        $panti->update([
            'verification_status' => Panti::VERIFICATION_VERIFIED,
            'verification_note' => null,
        ]);

        ActivityLog::record('panti.verified', "Admin memverifikasi {$panti->name}.", $panti);

        return back()->with('success', "{$panti->name} berhasil diverifikasi.");
    }

    public function reject(Request $request, Panti $panti)
    {
        $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $panti->update([
            'verification_status' => Panti::VERIFICATION_REJECTED,
            'verification_note' => $request->input('note') ?: 'Data panti belum dapat diverifikasi. Silakan lengkapi profil kembali.',
        ]);

        ActivityLog::record('panti.rejected', "Admin menolak verifikasi {$panti->name}.", $panti);

        return back()->with('success', "Verifikasi {$panti->name} ditandai ditolak.");
    }

    private function isCompleteForVerification(Panti $panti): bool
    {
        return $panti->consent_agreement
            && filled($panti->name)
            && filled($panti->type)
            && filled($panti->province)
            && filled($panti->city)
            && $panti->capacity !== null
            && $panti->total_residents !== null
            && $panti->children_count !== null
            && $panti->elderly_count !== null
            && $panti->staff_count !== null;
    }
}
