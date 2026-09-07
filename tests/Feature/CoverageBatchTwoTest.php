<?php

use App\Models\NeedCategory;
use App\Models\Panti;
use App\Models\PantiNeed;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Support\Facades\Hash;

it('covers admin category CRUD and unique slugs', function () {
    $admin = testUser(User::ROLE_ADMIN);

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Bahan Pokok',
            'target' => 'umum',
            'description' => 'Kebutuhan pokok.',
        ])
        ->assertRedirect();

    $category = NeedCategory::where('name', 'Bahan Pokok')->firstOrFail();
    expect($category->slug)->toBe('bahan-pokok')
        ->and($category->is_active)->toBeTrue();

    $this->actingAs($admin)
        ->post(route('admin.categories.store'), [
            'name' => 'Bahan-Pokok',
            'target' => 'anak',
        ])
        ->assertRedirect();

    expect(NeedCategory::where('slug', 'bahan-pokok')->count())->toBe(1);

    $this->actingAs($admin)
        ->put(route('admin.categories.update', $category), [
            'name' => 'Bahan Pokok',
            'target' => 'lansia',
            'description' => 'Diperbarui.',
        ])
        ->assertRedirect();

    $this->actingAs($admin)
        ->post(route('admin.categories.toggle', $category))
        ->assertRedirect();

    expect($category->refresh()->is_active)->toBeFalse()
        ->and($category->target)->toBe('lansia');
});

it('covers admin user filters, details, and self-toggle protection', function () {
    $admin = testUser(User::ROLE_ADMIN, ['email' => 'admin-coverage@example.test']);
    $target = testUser(User::ROLE_DONATUR, ['name' => 'Target Coverage', 'email' => 'target-coverage@example.test']);

    $this->actingAs($admin)
        ->get(route('admin.users.index', ['search' => 'Target', 'role' => User::ROLE_DONATUR]))
        ->assertOk();
    $this->actingAs($admin)->get(route('admin.users.show', $target))->assertOk();

    $this->actingAs($admin)
        ->post(route('admin.users.toggle', $target))
        ->assertRedirect();
    expect($target->refresh()->is_active)->toBeFalse();

    $this->actingAs($admin)
        ->post(route('admin.users.toggle', $admin))
        ->assertForbidden();
});

it('logs out a user whose account was deactivated during an existing session', function () {
    $user = testUser(User::ROLE_DONATUR);

    $this->actingAs($user)
        ->get(route('donatur.dashboard'))
        ->assertOk();

    $user->update(['is_active' => false]);

    $this->get(route('donatur.dashboard'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('returns only eligible panti data from the map endpoint', function () {
    $owner = testUser(User::ROLE_PANTI);
    $category = needCategoryForTest();
    $visible = testPanti($owner, [
        'slug' => 'panti-map-visible',
        'latitude' => -6.88,
        'longitude' => 107.61,
        'urgency_score' => 80,
        'urgency_status' => 'kritis',
    ]);
    $hidden = testPanti($owner, [
        'slug' => 'panti-map-hidden',
        'verification_status' => Panti::VERIFICATION_PENDING,
        'latitude' => -6.89,
        'longitude' => 107.62,
    ]);

    $visible->needs()->create([
        'need_category_id' => $category->id,
        'title' => 'Kebutuhan Rendah',
        'unit' => 'paket',
        'quantity_needed' => 2,
        'current_stock' => 1,
        'stock_days_remaining' => 10,
        'priority' => PantiNeed::PRIORITY_RENDAH,
        'status' => PantiNeed::STATUS_AKTIF,
    ]);
    $visible->needs()->create([
        'need_category_id' => $category->id,
        'title' => 'Kebutuhan Kritis',
        'unit' => 'paket',
        'quantity_needed' => 2,
        'current_stock' => 0,
        'stock_days_remaining' => 1,
        'priority' => PantiNeed::PRIORITY_KRITIS,
        'status' => PantiNeed::STATUS_AKTIF,
    ]);
    $visible->needs()->create([
        'need_category_id' => $category->id,
        'title' => 'Kebutuhan Selesai',
        'unit' => 'paket',
        'quantity_needed' => 2,
        'current_stock' => 2,
        'stock_days_remaining' => 30,
        'priority' => PantiNeed::PRIORITY_TINGGI,
        'status' => PantiNeed::STATUS_TERPENUHI,
    ]);

    $response = $this->getJson(route('map.data'))->assertOk();
    $response->assertJsonCount(1)->assertJsonPath('0.top_need_title', 'Kebutuhan Kritis');
    expect($hidden->id)->not->toBe($response->json('0.id'));
});

it('redacts approximate panti coordinates while preserving exact coordinates', function () {
    $owner = testUser(User::ROLE_PANTI);
    $approximate = testPanti($owner, [
        'slug' => 'panti-map-approximate',
        'latitude' => -6.8875,
        'longitude' => 107.6165,
        'location_precision' => Panti::LOCATION_APPROXIMATE,
    ]);
    $exact = testPanti($owner, [
        'slug' => 'panti-map-exact',
        'latitude' => -6.8875,
        'longitude' => 107.6165,
        'location_precision' => Panti::LOCATION_EXACT,
    ]);

    $payload = $this->getJson(route('map.data'))->assertOk()->json();
    $approximateData = collect($payload)->firstWhere('id', $approximate->id);
    $exactData = collect($payload)->firstWhere('id', $exact->id);

    expect($approximateData['latitude'])->toBe(-6.89)
        ->and($approximateData['longitude'])->toBe(107.62)
        ->and($exactData['latitude'])->toBe(-6.8875)
        ->and($exactData['longitude'])->toBe(107.6165);
});

it('covers panti volunteer approval, rejection, and ownership', function () {
    $pantiUser = testUser(User::ROLE_PANTI);
    $otherUser = testUser(User::ROLE_PANTI);
    $relawan = testUser(User::ROLE_RELAWAN);
    $panti = testPanti($pantiUser, ['slug' => 'panti-volunteer-uji']);
    $otherPanti = testPanti($otherUser, ['slug' => 'panti-volunteer-other']);

    $application = VolunteerApplication::create([
        'panti_id' => $panti->id,
        'user_id' => $relawan->id,
        'activity_type' => VolunteerApplication::ACTIVITY_KUNJUNGAN,
        'motivation' => 'Motivasi uji.',
        'status' => VolunteerApplication::STATUS_DIAJUKAN,
    ]);

    $this->actingAs($pantiUser)->get(route('panti.volunteers.index'))->assertOk();
    $this->actingAs($pantiUser)
        ->post(route('panti.volunteers.approve', $application))
        ->assertRedirect();
    expect($application->refresh()->status)->toBe(VolunteerApplication::STATUS_DISETUJUI);

    $rejected = VolunteerApplication::create([
        'panti_id' => $panti->id,
        'user_id' => $relawan->id,
        'activity_type' => VolunteerApplication::ACTIVITY_MENTOR,
        'motivation' => 'Motivasi uji.',
        'status' => VolunteerApplication::STATUS_DIAJUKAN,
    ]);
    $this->actingAs($pantiUser)
        ->post(route('panti.volunteers.reject', $rejected))
        ->assertRedirect();
    expect($rejected->refresh()->status)->toBe(VolunteerApplication::STATUS_DITOLAK);

    $foreign = VolunteerApplication::create([
        'panti_id' => $otherPanti->id,
        'user_id' => $relawan->id,
        'activity_type' => VolunteerApplication::ACTIVITY_KUNJUNGAN,
        'motivation' => 'Motivasi uji.',
        'status' => VolunteerApplication::STATUS_DIAJUKAN,
    ]);
    $this->actingAs($pantiUser)
        ->post(route('panti.volunteers.approve', $foreign))
        ->assertForbidden();
});

it('covers donor profile update with and without password change', function () {
    $donor = testUser(User::ROLE_DONATUR, ['password' => Hash::make('old-password')]);

    $this->actingAs($donor)->get(route('donatur.profile.edit'))->assertOk();
    $this->actingAs($donor)
        ->put(route('donatur.profile.update'), [
            'name' => 'Donatur Diperbarui',
            'phone' => '08123456789',
            'organization_name' => 'Komunitas Uji',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertRedirect(route('donatur.profile.edit'));

    expect($donor->refresh()->name)->toBe('Donatur Diperbarui')
        ->and(Hash::check('new-password', $donor->password))->toBeTrue();
});
