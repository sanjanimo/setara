<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Module;
use App\Models\ModuleLesson;
use App\Models\ModuleQuiz;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleContentController extends Controller
{
    public function storeLesson(Module $module, Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $data['sort_order'] = ($module->lessons()->max('sort_order') ?? 0) + 1;

        $lesson = $module->lessons()->create($data);

        ActivityLog::record('module.lesson.created', "Admin menambah materi '{$lesson->title}' di modul {$module->title}.", $module);

        return back()->with('success', 'Materi ditambahkan.');
    }

    public function updateLesson(Module $module, ModuleLesson $lesson, Request $request)
    {
        abort_unless($lesson->module_id === $module->id, 404);

        $lesson->update($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]));

        return back()->with('success', 'Materi diperbarui.');
    }

    public function destroyLesson(Module $module, ModuleLesson $lesson)
    {
        abort_unless($lesson->module_id === $module->id, 404);
        $lesson->delete();

        return back()->with('success', 'Materi dihapus.');
    }

    public function storeQuiz(Module $module, Request $request)
    {
        $data = $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', Rule::in(['a', 'b', 'c', 'd'])],
        ]);

        $module->quizzes()->create($data);

        ActivityLog::record('module.quiz.created', "Admin menambah kuis di modul {$module->title}.", $module);

        return back()->with('success', 'Kuis ditambahkan.');
    }

    public function updateQuiz(Module $module, ModuleQuiz $quiz, Request $request)
    {
        abort_unless($quiz->module_id === $module->id, 404);

        $quiz->update($request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', Rule::in(['a', 'b', 'c', 'd'])],
        ]));

        return back()->with('success', 'Kuis diperbarui.');
    }

    public function destroyQuiz(Module $module, ModuleQuiz $quiz)
    {
        abort_unless($quiz->module_id === $module->id, 404);
        $quiz->delete();

        return back()->with('success', 'Kuis dihapus.');
    }
}
