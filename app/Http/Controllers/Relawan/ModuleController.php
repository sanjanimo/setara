<?php

namespace App\Http\Controllers\Relawan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Module;
use App\Models\ModuleAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Menampilkan daftar modul untuk relawan (Jalur Dasar & Lanjutan)
     */
    public function index()
    {
        // Mengambil modul yang dipublikasikan beserta perhitungan jumlah lessons & quizzes
        $modules = Module::where('is_published', true)
            ->withCount(['lessons', 'quizzes'])
            ->orderBy('sort_order', 'asc')
            ->get();

        // Mengambil riwayat kelulusan kuis relawan yang sedang login
        $attempts = ModuleAttempt::where('user_id', Auth::id())
            ->where('passed', true)
            ->pluck('passed', 'module_id')
            ->toArray();

        return view('dashboard.relawan.modules.index', compact('modules', 'attempts'));
    }

    /**
     * Menampilkan detail isi modul
     */
    public function show(Module $module)
    {
        if (!$module->is_published) {
            abort(404);
        }

        $module->load(['lessons' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }, 'quizzes' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);

        $attempt = ModuleAttempt::where('user_id', Auth::id())
            ->where('module_id', $module->id)
            ->first();

        return view('dashboard.relawan.modules.show', compact('module', 'attempt'));
    }

    /**
     * Memproses percobaan kuis modul
     */
    public function attempt(Request $request, Module $module)
    {
        if (! $module->is_published) {
            abort(404);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $module->load('quizzes');
        $answers = $request->input('answers', []);

        $totalQuestions = $module->quizzes->count();
        if ($totalQuestions === 0) {
            return back()->with('error', 'Modul ini belum memiliki kuis.');
        }

        $correctCount = 0;
        foreach ($module->quizzes as $quiz) {
            if ($quiz->isCorrect($answers[$quiz->id] ?? null)) {
                $correctCount++;
            }
        }

        $score = (int) round(($correctCount / $totalQuestions) * 100);
        $passed = $score >= 80;

        $user->moduleAttempts()->updateOrCreate(
            ['module_id' => $module->id],
            ['score' => $score, 'passed' => $passed, 'completed_at' => now()]
        );

        if ($passed) {
            ActivityLog::record('module.completed', 'Relawan ' . $user->name . " lulus modul {$module->title} (skor {$score}).", $module);
        }

        return redirect()
            ->action([self::class, 'show'], ['module' => $module->slug])
            ->with('quizResult', ['score' => $score, 'passed' => $passed]);
    }
}
