<?php

use App\Models\Donation;
use App\Models\NeedCategory;
use App\Models\PantiNeed;
use App\Models\User;

it('allows a donor to submit and view a donation', function () {
    $donor = testUser(User::ROLE_DONATUR, [
        'email' => 'donor-flow@example.test',
        'phone' => '08123456789',
    ]);
    $owner = testUser(User::ROLE_PANTI);
    $panti = testPanti($owner, ['slug' => 'panti-donasi-uji']);
    $category = NeedCategory::create([
        'name' => 'Kebutuhan Uji',
        'slug' => 'kebutuhan-uji',
        'target' => 'umum',
        'is_active' => true,
    ]);
    $need = PantiNeed::create([
        'panti_id' => $panti->id,
        'need_category_id' => $category->id,
        'title' => 'Beras',
        'unit' => 'kg',
        'quantity_needed' => 20,
        'current_stock' => 2,
        'stock_days_remaining' => 2,
        'priority' => 'tinggi',
        'status' => 'aktif',
    ]);

    $this->actingAs($donor)->get(route('donatur.donations.create', $panti->slug))->assertOk();

    $this->actingAs($donor)
        ->post(route('donatur.donations.store', $panti->slug), [
            'type' => 'barang',
            'panti_need_id' => $need->id,
            'quantity' => 5,
            'message' => 'Bantuan untuk kebutuhan uji.',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('donations', [
        'user_id' => $donor->id,
        'panti_id' => $panti->id,
        'panti_need_id' => $need->id,
        'status' => Donation::STATUS_DIAJUKAN,
    ]);

    $this->actingAs($donor)
        ->get(route('donatur.donations.index'))
        ->assertOk()
        ->assertSee('Panti Uji');
});

it('prevents a donor from donating to an unverified panti', function () {
    $donor = testUser(User::ROLE_DONATUR);
    $owner = testUser(User::ROLE_PANTI);
    $panti = testPanti($owner, [
        'slug' => 'panti-belum-verifikasi',
        'verification_status' => 'pending',
    ]);

    $this->actingAs($donor)
        ->get(route('donatur.donations.create', $panti->slug))
        ->assertNotFound();
});
