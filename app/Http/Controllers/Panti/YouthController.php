<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panti\SaveYouthProfileRequest;
use App\Models\ActivityLog;
use App\Models\Panti;
use App\Models\YouthProfile;
use Illuminate\Support\Facades\Auth;

class YouthController extends Controller
{
    public function index()
    {
        $panti = $this->pantiOrFail();
        $youths = $panti->youthProfiles()->latest()->get();

        return view('dashboard.panti.youth.index', compact('panti', 'youths'));
    }

    public function create()
    {
        $this->pantiOrFail();

        return view('dashboard.panti.youth.create');
    }

    public function store(SaveYouthProfileRequest $request)
    {
        $panti = $this->pantiOrFail();

        $youth = $panti->youthProfiles()->create($request->validated());

        ActivityLog::record('youth.created', "Panti {$panti->name} menambahkan profil youth {$youth->initials}.", $youth);

        return redirect()->action([self::class, 'index'])->with('success', 'Profil youth berhasil ditambahkan.');
    }

    public function edit(YouthProfile $youth)
    {
        $this->authorizeYouth($youth);

        return view('dashboard.panti.youth.edit', compact('youth'));
    }

    public function update(YouthProfile $youth, SaveYouthProfileRequest $request)
    {
        $this->authorizeYouth($youth);

        $youth->update($request->validated());

        ActivityLog::record('youth.updated', "Panti {$youth->panti->name} memperbarui profil youth {$youth->initials}.", $youth);

        return redirect()->back()->with('success', 'Profil youth berhasil diperbarui.');
    }

    protected function pantiOrFail(): Panti
    {
        $panti = Auth::user()->panti;
        abort_unless($panti, 404, 'Profil panti belum dibuat.');

        return $panti;
    }

    protected function authorizeYouth(YouthProfile $youth): void
    {
        $panti = $this->pantiOrFail();
        abort_unless($youth->panti_id === $panti->id, 403);
    }
}
