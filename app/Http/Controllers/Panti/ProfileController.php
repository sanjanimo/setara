<?php

namespace App\Http\Controllers\Panti;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panti\SavePantiProfileRequest;
use App\Models\Panti;
use App\Services\UrgencyService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    public function edit()
    {
        $panti = Auth::user()->panti;

        return view('dashboard.panti.profile', compact('panti'));
    }

    public function update(SavePantiProfileRequest $request, UrgencyService $urgencyService)
    {
        $data = $request->validated();

        $user = Auth::user();
        $panti = $user->panti;

        if ($panti) {
            if ($panti->verification_status === Panti::VERIFICATION_REJECTED) {
                $data['verification_status'] = Panti::VERIFICATION_PENDING;
                $data['verification_note'] = null;
            }

            $panti->update($data);
        } else {
            $data['user_id'] = $user->id;
            $data['slug'] = $this->generateUniqueSlug($data['name']);
            $data['verification_status'] = Panti::VERIFICATION_PENDING;

            $panti = Panti::create($data);
        }

        $urgencyService->refresh($panti);

        ActivityLog::record(
            'panti.profile.updated',
            "Profil panti {$panti->name} diperbarui.",
            $panti
        );

        return redirect()
            ->route('panti.profile.edit')
            ->with('success', 'Profil panti berhasil disimpan.');
    }

    protected function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $suffix = 1;

        while (Panti::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}
