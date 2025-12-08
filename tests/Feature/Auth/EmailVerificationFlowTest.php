<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;

test('unverified user sees verification prompt', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)
        ->get(route('verification.notice'));

    $response->assertOk()
        ->assertViewIs('auth.verify-email');
});

test('verified user is redirected from verification prompt', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->get(route('verification.notice'));

    $response->assertRedirect(route('dashboard'));
});

test('user can request new verification email if not verified', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)
        ->post(route('verification.send'));

    $response->assertRedirect();
    $response->assertSessionHas('status', 'verification-link-sent');
});

test('already verified user is redirected when requesting new verification email', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->post(route('verification.send'));

    $response->assertRedirect(route('dashboard'));
});

test('verify email marks user as verified and fires event', function () {
    Event::fake();

    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    $response->assertRedirect(route('dashboard', [], false) . '?verified=1');
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();

    Event::assertDispatched(Verified::class);
});
