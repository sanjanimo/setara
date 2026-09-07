<?php

use App\Models\ModuleLesson;
use App\Models\ModuleQuiz;
use App\Models\User;

it('allows an admin to manage module metadata', function () {
    $admin = testUser(User::ROLE_ADMIN);
    $module = testModule();

    $this->actingAs($admin)->get(route('admin.modules.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.modules.show', $module->slug))->assertOk();
    $this->actingAs($admin)->get(route('admin.modules.edit', $module->slug))->assertOk();

    $this->actingAs($admin)
        ->put(route('admin.modules.update', $module->slug), [
            'title' => 'Modul Diperbarui',
            'target' => 'umum',
            'level' => 'advanced',
            'description' => 'Deskripsi baru.',
            'sort_order' => 2,
        ])
        ->assertRedirect(route('admin.modules.edit', $module->slug));

    $this->assertDatabaseHas('modules', [
        'id' => $module->id,
        'title' => 'Modul Diperbarui',
        'level' => 'advanced',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.modules.toggle', $module->slug))
        ->assertRedirect();

    expect($module->refresh()->is_published)->toBeFalse();
});

it('allows an admin to manage module lessons and quizzes', function () {
    $admin = testUser(User::ROLE_ADMIN);
    $module = testModule();

    $this->actingAs($admin)
        ->post(route('admin.modules.lessons.store', $module->slug), [
            'title' => 'Materi Baru',
            'content' => 'Isi materi.',
        ])
        ->assertRedirect();

    $lesson = ModuleLesson::firstOrFail();
    $this->actingAs($admin)
        ->put(route('admin.modules.lessons.update', [$module->slug, $lesson]), [
            'title' => 'Materi Diperbarui',
            'content' => 'Isi diperbarui.',
        ])
        ->assertRedirect();

    $this->actingAs($admin)
        ->delete(route('admin.modules.lessons.destroy', [$module->slug, $lesson]))
        ->assertRedirect();

    $this->actingAs($admin)
        ->post(route('admin.modules.quizzes.store', $module->slug), [
            'question' => 'Pertanyaan?',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'a',
        ])
        ->assertRedirect();

    $quiz = ModuleQuiz::firstOrFail();
    $this->actingAs($admin)
        ->put(route('admin.modules.quizzes.update', [$module->slug, $quiz]), [
            'question' => 'Pertanyaan baru?',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'b',
        ])
        ->assertRedirect();

    $this->actingAs($admin)
        ->delete(route('admin.modules.quizzes.destroy', [$module->slug, $quiz]))
        ->assertRedirect();
});
