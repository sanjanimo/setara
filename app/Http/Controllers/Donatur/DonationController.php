<?php

namespace App\Http\Controllers\Donatur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Donatur\StoreDonationRequest;
use App\Models\ActivityLog;
use App\Models\Donation;
use App\Models\Panti;
use App\Models\PantiNeed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function create(Panti $panti)
    {
        abort_unless($panti->isVerified(), 404);

        $needs = $panti->needs()
            ->active()
            ->with('category')
            ->get()
            ->sortBy(function ($need) {
                return array_search($need->priority, ['kritis', 'tinggi', 'sedang', 'rendah']);
            })
            ->values();

        $selectedNeedId = request()->integer('need_id');

        return view('donatur.donations.create', compact('panti', 'needs', 'selectedNeedId'));
    }

    public function store(Panti $panti, StoreDonationRequest $request)
    {
        abort_unless($panti->isVerified(), 404);

        $validated = $request->validated();

        $need = null;

        if ($validated['type'] === 'barang') {
            $need = PantiNeed::find($validated['panti_need_id']);

            if (! $need || $need->panti_id !== $panti->id || $need->status !== PantiNeed::STATUS_AKTIF) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'panti_need_id' => 'Kebutuhan yang dipilih tidak valid atau sudah tidak aktif.',
                    ]);
            }
        }

        $user = Auth::user();

        $donation = $panti->donations()->create([
            'panti_need_id' => $validated['panti_need_id'] ?? null,
            'user_id' => $user->id,
            'donor_name' => $user->name,
            'donor_email' => $user->email,
            'donor_phone' => $user->phone,
            'type' => $validated['type'],
            'quantity' => $validated['quantity'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => Donation::STATUS_DIAJUKAN,
        ]);

        ActivityLog::record(
            'donation.submitted',
            "Donatur {$user->name} mengajukan niat bantuan {$donation->type} untuk {$panti->name}.",
            $donation
        );

        return redirect()
            ->back()
            ->with('success', 'Niat bantuan kamu berhasil dikirim dan menunggu konfirmasi panti.');
    }

    public function index(Request $request)
    {
        $query = Donation::with(['panti', 'need'])
            ->where('user_id', Auth::id());

    // 1. Search nama donatur, email, atau panti penerima
        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->whereHas('panti', fn ($p) => $p->where('name', 'like', $s));
        }

    // 2. Filter berdasarkan status donasi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

    // 3. Paginate + withQueryString
        $donations = $query->latest()->paginate(15)->withQueryString();

        return view('donatur.donations.index', compact('donations'));
    }
}
