<?php

use App\Models\City;
use App\Models\Donation;
use App\Models\District;
use App\Models\NeedCategory;
use App\Models\Panti;
use App\Models\PantiNeed;
use App\Models\Province;
use App\Models\User;
use Illuminate\Support\Facades\Http;

function profilePayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Panti Profil Uji',
        'type' => Panti::TYPE_ANAK,
        'description' => 'Profil untuk pengujian.',
        'province' => 'Jawa Barat',
        'city' => 'Bandung',
        'district' => 'Coblong',
        'latitude' => -6.88,
        'longitude' => 107.61,
        'address' => 'Alamat uji',
        'capacity' => 20,
        'total_residents' => 10,
        'children_count' => 10,
        'elderly_count' => 0,
        'staff_count' => 3,
        'location_precision' => Panti::LOCATION_APPROXIMATE,
        'consent_agreement' => 1,
    ], $overrides);
}

it('shows only verified panti needs on the landing page', function () {
    $verifiedOwner = testUser(User::ROLE_PANTI);
    $pendingOwner = testUser(User::ROLE_PANTI);
    $verified = testPanti($verifiedOwner, ['slug' => 'panti-landing-verified']);
    $pending = testPanti($pendingOwner, [
        'slug' => 'panti-landing-pending',
        'verification_status' => Panti::VERIFICATION_PENDING,
    ]);
    $category = needCategoryForTest();
    $needData = [
        'need_category_id' => $category->id,
        'unit' => 'paket',
        'quantity_needed' => 10,
        'current_stock' => 0,
        'stock_days_remaining' => 2,
        'priority' => PantiNeed::PRIORITY_KRITIS,
        'status' => PantiNeed::STATUS_AKTIF,
    ];

    $verified->needs()->create(array_merge($needData, ['title' => 'Kebutuhan Public']));
    $pending->needs()->create(array_merge($needData, ['title' => 'Kebutuhan Private']));

    $this->get('/')
        ->assertOk()
        ->assertSee('Kebutuhan Public')
        ->assertDontSee('Kebutuhan Private');
});

it('covers admin verification approval and rejection branches', function () {
    $admin = testUser(User::ROLE_ADMIN);
    $owner = testUser(User::ROLE_PANTI);
    $pending = testPanti($owner, [
        'slug' => 'panti-verifikasi-uji',
        'verification_status' => Panti::VERIFICATION_PENDING,
        'consent_agreement' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.verification.index', ['status' => 'pending', 'search' => 'Panti']))
        ->assertOk();

    $this->actingAs($admin)
        ->post(route('admin.verification.approve', $pending))
        ->assertRedirect();

    expect($pending->refresh()->verification_status)->toBe(Panti::VERIFICATION_VERIFIED);

    $rejected = testPanti($owner, [
        'slug' => 'panti-ditolak-uji',
        'verification_status' => Panti::VERIFICATION_PENDING,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.verification.reject', $rejected), ['note' => 'Lengkapi data.'])
        ->assertRedirect();

    expect($rejected->refresh()->verification_status)->toBe(Panti::VERIFICATION_REJECTED)
        ->and($rejected->verification_note)->toBe('Lengkapi data.');
});

it('rejects verification when public consent is missing', function () {
    $admin = testUser(User::ROLE_ADMIN);
    $owner = testUser(User::ROLE_PANTI);
    $panti = testPanti($owner, [
        'slug' => 'panti-tanpa-consent',
        'verification_status' => Panti::VERIFICATION_PENDING,
        'consent_agreement' => false,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.verification.approve', $panti))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect($panti->refresh()->verification_status)->toBe(Panti::VERIFICATION_PENDING);
});

it('covers panti donation confirmation rejection and completion', function () {
    $pantiUser = testUser(User::ROLE_PANTI);
    $donor = testUser(User::ROLE_DONATUR);
    $panti = testPanti($pantiUser, ['slug' => 'panti-lifecycle-uji']);

    $confirmed = donationForTest($panti, $donor);
    $this->actingAs($pantiUser)
        ->post(route('panti.donations.confirm', $confirmed))
        ->assertRedirect();
    expect($confirmed->refresh()->status)->toBe(Donation::STATUS_DIKONFIRMASI);

    $this->actingAs($pantiUser)
        ->post(route('panti.donations.complete', $confirmed))
        ->assertRedirect();
    expect($confirmed->refresh()->status)->toBe(Donation::STATUS_SELESAI);

    $rejected = donationForTest($panti, $donor);
    $this->actingAs($pantiUser)
        ->post(route('panti.donations.reject', $rejected))
        ->assertRedirect();
    expect($rejected->refresh()->status)->toBe(Donation::STATUS_DITOLAK)
        ->and($rejected->note)->not->toBeNull();
});

it('covers panti need creation, fulfilled state, update, and ownership', function () {
    $pantiUser = testUser(User::ROLE_PANTI);
    $otherUser = testUser(User::ROLE_PANTI);
    $panti = testPanti($pantiUser, ['slug' => 'panti-needs-uji']);
    $otherPanti = testPanti($otherUser, ['slug' => 'panti-needs-other']);
    $category = needCategoryForTest();
    $payload = [
        'need_category_id' => $category->id,
        'title' => 'Kebutuhan Terpenuhi',
        'unit' => 'paket',
        'quantity_needed' => 10,
        'current_stock' => 10,
        'stock_days_remaining' => 14,
        'priority' => PantiNeed::PRIORITY_SEDANG,
        'status' => PantiNeed::STATUS_TERPENUHI,
    ];

    $this->actingAs($pantiUser)
        ->post(route('panti.needs.store'), $payload)
        ->assertRedirect(route('panti.needs.index'));

    $need = $panti->needs()->firstOrFail();
    expect($need->fulfilled_at)->not->toBeNull();

    $this->actingAs($pantiUser)
        ->put(route('panti.needs.update', $need), array_merge($payload, [
            'title' => 'Kebutuhan Aktif',
            'status' => PantiNeed::STATUS_AKTIF,
        ]))
        ->assertRedirect(route('panti.needs.index'));

    expect($need->refresh()->fulfilled_at)->toBeNull();

    $otherNeed = $otherPanti->needs()->create(array_merge($payload, [
        'need_category_id' => $category->id,
    ]));
    $this->actingAs($pantiUser)
        ->get(route('panti.needs.edit', $otherNeed))
        ->assertForbidden();
});

it('covers panti profile creation and rejected profile recovery', function () {
    $pantiUser = testUser(User::ROLE_PANTI);

    $this->actingAs($pantiUser)
        ->put(route('panti.profile.update'), profilePayload())
        ->assertRedirect(route('panti.profile.edit'));

    $panti = $pantiUser->panti()->firstOrFail();
    expect($panti->slug)->toBe('panti-profil-uji')
        ->and($panti->verification_status)->toBe(Panti::VERIFICATION_PENDING);

    $panti->update([
        'verification_status' => Panti::VERIFICATION_REJECTED,
        'verification_note' => 'Perlu perbaikan.',
    ]);

    $pantiUser = $pantiUser->fresh();

    $this->actingAs($pantiUser)
        ->put(route('panti.profile.update'), profilePayload(['name' => 'Panti Profil Diperbarui']))
        ->assertRedirect(route('panti.profile.edit'));

    expect($panti->refresh()->verification_status)->toBe(Panti::VERIFICATION_PENDING)
        ->and($panti->verification_note)->toBeNull();
});

it('covers local location data and external fallback', function () {
    $province = Province::create(['name' => 'Jawa Barat', 'code' => '32']);
    $city = City::create(['province_id' => $province->id, 'name' => 'Bandung', 'type' => 'Kota']);
    District::create(['city_id' => $city->id, 'name' => 'Coblong']);

    $this->getJson('/api/provinces')->assertOk()->assertJsonFragment(['name' => 'Jawa Barat']);
    $this->getJson('/api/cities?province_id=' . $province->id)->assertOk()->assertJsonFragment(['type' => 'Kota']);
    $this->getJson('/api/districts?city_id=' . $city->id)->assertOk()->assertJsonFragment(['name' => 'Coblong']);

    Http::fake([
        'https://www.emsifa.com/*' => Http::response([
            ['id' => '999', 'name' => 'Fallback'],
        ]),
    ]);

    $this->getJson('/api/cities?province_id=999')->assertOk()->assertJsonPath('0.name', 'Fallback');
    $this->getJson('/api/districts?city_id=999')->assertOk()->assertJsonPath('0.name', 'Fallback');
    $this->getJson('/api/cities')->assertStatus(422);
    $this->getJson('/api/districts')->assertStatus(422);
});
