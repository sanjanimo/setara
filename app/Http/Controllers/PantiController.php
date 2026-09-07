<?php

namespace App\Http\Controllers;

use App\Models\Panti;

class PantiController extends Controller
{
    public function show(string $slug)
    {
        $panti = Panti::verified()
            ->where('slug', $slug)
            ->with('needs.category')
            ->firstOrFail();

        $needs = $panti->needs
            ->where('status', 'aktif')
            ->sortBy(function ($need) {
                return array_search($need->priority, ['kritis', 'tinggi', 'sedang', 'rendah']);
            })
            ->values();

        return view('panti.show', compact('panti', 'needs'));
    }
}
