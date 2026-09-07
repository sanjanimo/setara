<?php

use App\Models\ModuleAttempt;
use App\Models\ModuleQuiz;
use App\Models\NeedCategory;
use App\Models\PantiNeed;
use App\Models\User;
use App\Services\UrgencyService;
use Illuminate\Support\Carbon;

it('calculates urgency from supply, capacity, vulnerability, and freshness', function () {
    Carbon::setTestNow(now()->startOfDay());

    $owner = testUser(User::ROLE_PANTI);
    $panti = testPanti($owner, [
        'type' => 'anak',
        'capacity' => 10,
        'total_residents' => 12,
        'updated_at' => now()->subDays(3),
    ]);
    $category = NeedCategory::create([
        'name' => 'Makanan',
        'slug' => 'makanan-uji',
        'target' => 'umum',
        'is_active' => true,
    ]);
    PantiNeed::create([
        'panti_id' => $panti->id,
        'need_category_id' => $category->id,
        'title' => 'Beras',
        'stock_days_remaining' => 2,
        'quantity_needed' => 10,
        'current_stock' => 2,
        'priority' => PantiNeed::PRIORITY_KRITIS,
        'status' => PantiNeed::STATUS_AKTIF,
    ]);

    app(UrgencyService::class)->refresh($panti);

    expect($panti->refresh()->urgency_score)->toBe(92)
        ->and($panti->urgency_status)->toBe('kritis');
});

it('serves the panti detail through singular and plural public URLs', function () {
    $owner = testUser(User::ROLE_PANTI);
    $panti = testPanti($owner, ['slug' => 'panti-detail-uji']);

    $this->get('/panti/' . $panti->slug)->assertOk();
    $this->get('/pantis/' . $panti->slug)->assertOk();
});

it('records a passing quiz attempt for a relawan', function () {
    $relawan = testUser(User::ROLE_RELAWAN);
    $module = testModule();
    $quiz = ModuleQuiz::create([
        'module_id' => $module->id,
        'question' => 'Pertanyaan uji?',
        'option_a' => 'Benar',
        'option_b' => 'Salah',
        'option_c' => 'Lainnya',
        'option_d' => 'Tidak tahu',
        'correct_option' => 'a',
    ]);

    $this->actingAs($relawan)
        ->post(route('relawan.modules.attempt', $module->slug), [
            'answers' => [$quiz->id => 'a'],
        ])
        ->assertRedirect(route('relawan.modules.show', $module->slug));

    $this->assertDatabaseHas('module_attempts', [
        'user_id' => $relawan->id,
        'module_id' => $module->id,
        'score' => 100,
        'passed' => true,
    ]);
});

it('blocks a relawan from applying before required modules are passed', function () {
    $relawan = testUser(User::ROLE_RELAWAN);
    $owner = testUser(User::ROLE_PANTI);
    $panti = testPanti($owner, ['slug' => 'panti-gate-uji']);
    testModule('umum');
    testModule('anak');

    $this->actingAs($relawan)
        ->post(route('relawan.applications.store', $panti->slug), [
            'activity_type' => 'kunjungan',
            'motivation' => 'Saya ingin membantu.',
        ])
        ->assertForbidden();

    $this->assertDatabaseCount('volunteer_applications', 0);
});

it('allows a relawan to apply after passing required modules', function () {
    $relawan = testUser(User::ROLE_RELAWAN);
    $owner = testUser(User::ROLE_PANTI);
    $panti = testPanti($owner, ['slug' => 'panti-gate-lulus-uji']);
    $modules = [testModule('umum'), testModule('anak')];

    foreach ($modules as $module) {
        ModuleAttempt::create([
            'user_id' => $relawan->id,
            'module_id' => $module->id,
            'score' => 100,
            'passed' => true,
            'completed_at' => now(),
        ]);
    }

    $this->actingAs($relawan)
        ->post(route('relawan.applications.store', $panti->slug), [
            'activity_type' => 'kunjungan',
            'motivation' => 'Saya ingin membantu.',
        ])
        ->assertRedirect(route('relawan.applications.index'));

    $this->assertDatabaseHas('volunteer_applications', [
        'user_id' => $relawan->id,
        'panti_id' => $panti->id,
        'status' => 'diajukan',
    ]);
});
