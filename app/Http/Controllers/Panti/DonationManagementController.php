<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Donation;
use App\Models\Panti;
use Illuminate\Support\Facades\Auth;

class DonationManagementController extends Controller
{
    public function index()
    {
        $panti = $this->pantiOrFail();

        $donations = $panti->donations()
            ->with(['need.category', 'user'])
            ->latest()
            ->get();

        return view('dashboard.panti.donations.index', compact('panti', 'donations'));
    }

    public function confirm(Donation $donation)
    {
        $this->authorizeDonation($donation);

        if ($donation->status !== Donation::STATUS_DIAJUKAN) {
            return back()->with('error', 'Status bantuan tidak valid untuk aksi ini.');
        }

        $donation->update([
            'status' => Donation::STATUS_DIKONFIRMASI,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        ActivityLog::record(
            'donation.confirmed',
            "Panti {$donation->panti->name} mengonfirmasi niat bantuan dari {$donation->donor_name}.",
            $donation
        );

        return back()->with('success', 'Bantuan berhasil dikonfirmasi. Silakan koordinasi penyaluran dengan donatur.');
    }

    public function reject(Donation $donation)
    {
        $this->authorizeDonation($donation);

        if ($donation->status !== Donation::STATUS_DIAJUKAN) {
            return back()->with('error', 'Status bantuan tidak valid untuk aksi ini.');
        }

        $donation->update([
            'status' => Donation::STATUS_DITOLAK,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'note' => $donation->note ?: 'Terima kasih atas niat baik Anda. Saat ini panti belum dapat menerima bantuan ini.',
        ]);

        ActivityLog::record(
            'donation.rejected',
            "Panti {$donation->panti->name} menandai niat bantuan dari {$donation->donor_name} sebagai belum dapat diterima.",
            $donation
        );

        return back()->with('success', 'Pengajuan bantuan ditandai belum dapat diterima.');
    }

    public function complete(Donation $donation)
    {
        $this->authorizeDonation($donation);

        if ($donation->status !== Donation::STATUS_DIKONFIRMASI) {
            return back()->with('error', 'Bantuan harus dikonfirmasi terlebih dahulu sebelum ditandai selesai.');
        }

        $donation->update([
            'status' => Donation::STATUS_SELESAI,
        ]);

        ActivityLog::record(
            'donation.completed',
            "Panti {$donation->panti->name} menandai niat bantuan dari {$donation->donor_name} sebagai selesai.",
            $donation
        );

        return back()->with('success', 'Bantuan berhasil ditandai selesai.');
    }

    protected function pantiOrFail(): Panti
    {
        $panti = Auth::user()->panti;

        abort_unless($panti, 404, 'Profil panti belum dibuat.');

        return $panti;
    }

    protected function authorizeDonation(Donation $donation): void
    {
        $panti = $this->pantiOrFail();

        abort_unless($donation->panti_id === $panti->id, 403);
    }
}
