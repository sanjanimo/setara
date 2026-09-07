<?php

use App\Models\ActivityLog;
use App\Models\Donation;
use App\Models\ModuleLesson;
use App\Models\ModuleAttempt;
use App\Models\ModuleQuiz;
use App\Models\User;
use App\Models\VisitReport;
use App\Models\VolunteerApplication;

function youthPayload(array $overrides = []): array
{
    return array_merge([
        'initials' => 'AB',
        'age' => 17,
        'interests' => 'Desain grafis',
        'skill_goals' => 'Portofolio digital',
        'training_needs' => 'Mentoring',
        'mentor_needed' => 1,
        'status' => 'baru',
        'note' => 'Catatan uji',
    ], $overrides);
}

it('covers panti youth profile CRUD and privacy validation', function () {
    $pantiUser = testUser(User::ROLE_PANTI);
    $otherUser = testUser(User::ROLE_PANTI);
    $panti = testPanti($pantiUser, ['slug' => 'panti-youth-uji']);
    $otherPanti = testPanti($otherUser, ['slug' => 'panti-youth-other']);

    $this->actingAs($pantiUser)->get(route('panti.youth.index'))->assertOk();
    $this->actingAs($pantiUser)->get(route('panti.youth.create'))->assertOk();
    $this->actingAs($pantiUser)
        ->post(route('panti.youth.store'), youthPayload())
        ->assertRedirect();

    $youth = $panti->youthProfiles()->firstOrFail();
    $this->actingAs($pantiUser)->get(route('panti.youth.edit', $youth))->assertOk();
    $this->actingAs($pantiUser)
        ->put(route('panti.youth.update', $youth), youthPayload(['initials' => 'CD', 'status' => 'didampingi']))
        ->assertRedirect();
    expect($youth->refresh()->initials)->toBe('CD');

    $foreign = $otherPanti->youthProfiles()->create(youthPayload(['initials' => 'EF']));
    $this->actingAs($pantiUser)
        ->get(route('panti.youth.edit', $foreign))
        ->assertForbidden();
});

it('rejects an unavailable youth on a volunteer mentor application', function () {
    $relawan = testUser(User::ROLE_RELAWAN);
    $owner = testUser(User::ROLE_PANTI);
    $panti = testPanti($owner, ['slug' => 'panti-youth-gate-uji']);
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

    $youth = $panti->youthProfiles()->create([
        'initials' => 'XY',
        'age' => 17,
        'interests' => 'Musik',
        'mentor_needed' => false,
        'status' => 'didampingi',
    ]);

    $this->actingAs($relawan)
        ->post(route('relawan.applications.store', $panti->slug), [
            'activity_type' => 'mentor',
            'youth_profile_id' => $youth->id,
            'motivation' => 'Saya ingin membantu.',
        ])
        ->assertSessionHasErrors('youth_profile_id');
});

it('covers admin donation and activity log filters', function () {
    $admin = testUser(User::ROLE_ADMIN);
    $owner = testUser(User::ROLE_PANTI);
    $donor = testUser(User::ROLE_DONATUR, ['name' => 'Donor Monitor']);
    $panti = testPanti($owner, ['slug' => 'panti-monitor-uji']);
    donationForTest($panti, $donor, Donation::STATUS_DIAJUKAN);
    ActivityLog::record('coverage.search', 'Catatan untuk pencarian coverage.', $donor);

    $this->actingAs($admin)
        ->get(route('admin.donations.index', ['search' => 'Donor Monitor', 'status' => Donation::STATUS_DIAJUKAN]))
        ->assertOk();
    $this->actingAs($admin)
        ->get(route('admin.logs.index', ['search' => 'coverage.search']))
        ->assertOk();
});

it('covers relawan module listing, unpublished guard, and failed quiz', function () {
    $relawan = testUser(User::ROLE_RELAWAN);
    $published = testModule('umum', 'basic', ['slug' => 'modul-published-uji']);
    ModuleLesson::create(['module_id' => $published->id, 'title' => 'Materi', 'content' => 'Isi', 'sort_order' => 1]);
    $quiz = ModuleQuiz::create([
        'module_id' => $published->id,
        'question' => 'Pertanyaan?',
        'option_a' => 'A',
        'option_b' => 'B',
        'option_c' => 'C',
        'option_d' => 'D',
        'correct_option' => 'a',
    ]);
    $unpublished = testModule('umum', 'advanced', ['slug' => 'modul-hidden-uji', 'is_published' => false]);
    $empty = testModule('umum', 'basic', ['slug' => 'modul-empty-uji']);

    $this->actingAs($relawan)->get(route('relawan.modules.index'))->assertOk();
    $this->actingAs($relawan)->get(route('relawan.modules.show', $published->slug))->assertOk();
    $this->actingAs($relawan)
        ->post(route('relawan.modules.attempt', $published->slug), ['answers' => [$quiz->id => 'b']])
        ->assertRedirect();
    expect($relawan->moduleAttempts()->where('module_id', $published->id)->value('passed'))->toBeFalse();

    $this->actingAs($relawan)
        ->get(route('relawan.modules.show', $unpublished->slug))
        ->assertNotFound();
    $this->actingAs($relawan)
        ->post(route('relawan.modules.attempt', $unpublished->slug))
        ->assertNotFound();
    $this->actingAs($relawan)
        ->post(route('relawan.modules.attempt', $empty->slug))
        ->assertRedirect();
});

it('covers panti and relawan report indexes', function () {
    $pantiUser = testUser(User::ROLE_PANTI);
    $relawan = testUser(User::ROLE_RELAWAN);
    $panti = testPanti($pantiUser, ['slug' => 'panti-report-uji']);
    $application = VolunteerApplication::create([
        'panti_id' => $panti->id,
        'user_id' => $relawan->id,
        'activity_type' => VolunteerApplication::ACTIVITY_KUNJUNGAN,
        'motivation' => 'Motivasi uji.',
        'status' => VolunteerApplication::STATUS_SELESAI,
    ]);
    VisitReport::create([
        'panti_id' => $panti->id,
        'user_id' => $relawan->id,
        'volunteer_application_id' => $application->id,
        'activity_date' => now()->toDateString(),
        'summary' => 'Laporan uji.',
        'follow_up_needed' => false,
    ]);

    $this->actingAs($pantiUser)->get(route('panti.reports.index'))->assertOk();
    $this->actingAs($relawan)->get(route('relawan.reports.index'))->assertOk();
});
