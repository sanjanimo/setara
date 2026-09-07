<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panti\SavePantiNeedRequest;
use App\Models\NeedCategory;
use App\Models\Panti;
use App\Models\PantiNeed;
use App\Services\UrgencyService;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class NeedController extends Controller
{
    public function index()
    {
        $panti = $this->panti();

        $needs = $panti
            ? $panti->needs()->with('category')->latest()->get()
            : collect();

        return view('dashboard.panti.needs.index', compact('panti', 'needs'));
    }

    public function create()
    {
        $this->pantiOrFail();

        $categories = NeedCategory::active()
            ->orderBy('name')
            ->get();

        return view('dashboard.panti.needs.create', compact('categories'));
    }

    public function store(SavePantiNeedRequest $request, UrgencyService $urgencyService)
    {
        $panti = $this->pantiOrFail();

        $data = $request->validated();

        $data['fulfilled_at'] = $data['status'] === PantiNeed::STATUS_TERPENUHI
            ? now()
            : null;

        $need = $panti->needs()->create($data);

        $urgencyService->refresh($panti);

        ActivityLog::record(
            'panti.need.created',
            "Panti {$panti->name} menambahkan kebutuhan '{$need->title}'.",
            $need
        );

        return redirect()
            ->route('panti.needs.index')
            ->with('success', 'Kebutuhan panti berhasil ditambahkan.');
    }

    public function edit(PantiNeed $need)
    {
        $this->authorizeNeed($need);

        $categories = NeedCategory::active()
            ->orderBy('name')
            ->get();

        return view('dashboard.panti.needs.edit', compact('need', 'categories'));
    }

    public function update(
        PantiNeed $need,
        SavePantiNeedRequest $request,
        UrgencyService $urgencyService
    ) {
        $this->authorizeNeed($need);

        $data = $request->validated();

        $data['fulfilled_at'] = $data['status'] === PantiNeed::STATUS_TERPENUHI
            ? now()
            : null;

        $need->update($data);

        $urgencyService->refresh($need->panti);

        ActivityLog::record(
            'panti.need.updated',
            "Panti {$need->panti->name} memperbarui kebutuhan '{$need->title}'.",
            $need
        );

        return redirect()
            ->route('panti.needs.index')
            ->with('success', 'Kebutuhan panti berhasil diperbarui.');
    }

    public function destroy(PantiNeed $need, UrgencyService $urgencyService)
    {
        $this->authorizeNeed($need);

        $panti = $need->panti;
        $needTitle = $need->title;

        $need->delete();

        $urgencyService->refresh($panti);

        ActivityLog::record(
            'panti.need.deleted',
            "Panti {$panti->name} menghapus kebutuhan '{$needTitle}'.",
            null,
            Auth::id()
        );

        return redirect()
            ->route('panti.needs.index')
            ->with('success', 'Kebutuhan panti berhasil dihapus.');
    }

    protected function panti(): ?Panti
    {
        return Auth::user()->panti;
    }

    protected function pantiOrFail(): Panti
    {
        $panti = $this->panti();

        abort_unless($panti !== null, 404, 'Profil panti belum dibuat.');

        return $panti;
    }

    protected function authorizeNeed(PantiNeed $need): void
    {
        $panti = $this->pantiOrFail();

        abort_unless($need->panti_id === $panti->id, 403);
    }
}
