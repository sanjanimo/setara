<?php

use App\Models\ActivityLog;
use App\Models\User;

it('registers a donor and redirects to the donor dashboard', function () {
    $response = $this->post('/register', [
        'name' => 'Donatur Uji',
        'email' => 'donatur-uji@example.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => User::ROLE_DONATUR,
        'phone' => '08123456789',
    ]);

    $response->assertRedirect(route('donatur.dashboard'));
    $this->assertAuthenticatedAs(User::where('email', 'donatur-uji@example.test')->first());
    $this->assertDatabaseHas('activity_logs', ['action' => 'auth.register']);
});

it('redirects an authenticated user to the dashboard for their role', function () {
    $user = testUser(User::ROLE_PANTI);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('panti.dashboard'));
});

it('rejects inactive credentials during login', function () {
    $user = testUser(User::ROLE_DONATUR, [
        'email' => 'inactive@example.test',
        'password' => bcrypt('password123'),
        'is_active' => false,
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('records logout and returns to home', function () {
    $user = testUser(User::ROLE_DONATUR);

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect(route('home'));

    $this->assertGuest();
    expect(ActivityLog::where('action', 'auth.logout')->exists())->toBeTrue();
});
