<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::withCount(['lessons', 'quizzes'])->orderBy('sort_order')->get();

        return view('dashboard.admin.modules.index', compact('modules'));
    }

    public function show(Module $module)
    {
        $module->load('lessons', 'quizzes');

        return view('dashboard.admin.modules.show', compact('module'));
    }

    public function edit(Module $module)
    {
        $module->load('lessons', 'quizzes');

        return view('dashboard.admin.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'target' => ['required', 'in:umum,anak,lansia'],
            'level' => ['required', 'in:basic,advanced'],
            'description' => ['required', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $module->update($validated);

        ActivityLog::record('module.updated', "Admin memperbarui modul {$module->title}.", $module);

        return redirect()->route('admin.modules.edit', $module->slug)
            ->with('success', "Modul {$module->title} berhasil diperbarui.");
    }

    public function toggle(Module $module)
    {
        $module->update(['is_published' => ! $module->is_published]);

        ActivityLog::record('module.toggled', 'Admin ' . ($module->is_published ? 'menerbitkan' : 'menyembunyikan') . " modul {$module->title}.", $module);

        return back()->with('success', "Status modul {$module->title} diperbarui.");
    }
}
