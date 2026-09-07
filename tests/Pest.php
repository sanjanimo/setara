<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

function testUser(string $role, array $attributes = []): \App\Models\User
{
    return \App\Models\User::factory()->create(array_merge([
        'role' => $role,
        'is_active' => true,
    ], $attributes));
}

function testPanti(\App\Models\User $owner, array $attributes = []): \App\Models\Panti
{
    return \App\Models\Panti::create(array_merge([
        'user_id' => $owner->id,
        'name' => 'Panti Uji',
        'slug' => 'panti-uji-' . fake()->unique()->slug(2),
        'type' => \App\Models\Panti::TYPE_ANAK,
        'province' => 'Jawa Barat',
        'city' => 'Bandung',
        'verification_status' => \App\Models\Panti::VERIFICATION_VERIFIED,
        'capacity' => 20,
        'total_residents' => 10,
        'location_precision' => \App\Models\Panti::LOCATION_APPROXIMATE,
    ], $attributes));
}

function testModule(string $target = 'umum', string $level = 'basic', array $attributes = []): \App\Models\Module
{
    return \App\Models\Module::create(array_merge([
        'title' => 'Modul Uji ' . fake()->unique()->word(),
        'slug' => 'modul-uji-' . fake()->unique()->slug(2),
        'target' => $target,
        'level' => $level,
        'description' => 'Modul untuk pengujian.',
        'is_published' => true,
        'sort_order' => 1,
    ], $attributes));
}

function needCategoryForTest(): \App\Models\NeedCategory
{
    return \App\Models\NeedCategory::create([
        'name' => 'Kategori ' . fake()->unique()->word(),
        'slug' => 'kategori-' . fake()->unique()->slug(2),
        'target' => 'umum',
        'is_active' => true,
    ]);
}

function donationForTest(\App\Models\Panti $panti, \App\Models\User $donor, string $status = \App\Models\Donation::STATUS_DIAJUKAN): \App\Models\Donation
{
    return \App\Models\Donation::create([
        'panti_id' => $panti->id,
        'user_id' => $donor->id,
        'donor_name' => $donor->name,
        'donor_email' => $donor->email,
        'type' => \App\Models\Donation::TYPE_TENAGA,
        'status' => $status,
    ]);
}
