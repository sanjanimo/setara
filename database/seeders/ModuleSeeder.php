<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require database_path('data/module_content.php') as $data) {
            $module = Module::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'target' => $data['target'],
                    'level' => $data['level'],
                    'description' => $data['description'],
                    'sort_order' => $data['sort_order'],
                    'is_published' => true,
                ]
            );

            $module->lessons()->delete();
            $module->quizzes()->delete();

            foreach ($data['lessons'] as $i => $lesson) {
                $module->lessons()->create([
                    'title' => $lesson['title'],
                    'content' => $lesson['content'],
                    'sort_order' => $i + 1,
                ]);
            }

            foreach ($data['quizzes'] as $quiz) {
                $module->quizzes()->create([
                    'question' => $quiz['question'],
                    'option_a' => $quiz['a'],
                    'option_b' => $quiz['b'],
                    'option_c' => $quiz['c'],
                    'option_d' => $quiz['d'],
                    'correct_option' => $quiz['correct'],
                ]);
            }
        }
    }
}
