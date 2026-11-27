<?php

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\Prep\RoleSeeder;
use Filament\Facades\Filament;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User as MicrosoftAccount;

function microsoftAccount(string $email): MicrosoftAccount
{
    $account = (new MicrosoftAccount)->map([
        'id' => 'microsoft-id-'.$email,
        'name' => 'Staff Member',
        'email' => $email,
    ]);

    $account->token = 'access-token';
    $account->refreshToken = 'refresh-token';
    $account->expiresIn = 3600;

    return $account;
}

function logInWithMicrosoft(string $email)
{
    Socialite::shouldReceive('driver->user')->andReturn(microsoftAccount($email));

    return test()->get(route('socialment.callback', ['provider' => 'azure']));
}

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('shows the Microsoft login button above the email form on the admin login page', function () {
    $this->get(Filament::getLoginUrl())
        ->assertOk()
        ->assertSee(route('socialment.redirect.panel', ['panelId' => 'admin', 'provider' => 'azure']))
        ->assertSeeInOrder(['Staff (via Microsoft)', 'Or login with email:', 'id="form.password"'], false);
});

it('shows a failed Microsoft login message on the login page', function () {
    $this->withSession(['socialment.error' => 'This account is not authorized to log in.'])
        ->get(Filament::getLoginUrl())
        ->assertSee('This account is not authorized to log in.');
});

it('creates an editor the first time someone logs in with Microsoft', function () {
    logInWithMicrosoft('newstaff@example.com')->assertRedirect();

    $user = User::where('email', 'newstaff@example.com')->firstOrFail();

    expect($user->hasRole(UserRole::Editor->value))->toBeTrue()
        ->and($user->isAdmin())->toBeFalse();

    $this->assertAuthenticatedAs($user);
});

it('logs an existing user in with Microsoft and keeps their role', function () {
    $admin = User::factory()->admin()->create(['email' => 'boss@example.com']);

    logInWithMicrosoft('boss@example.com')->assertRedirect();

    expect(User::where('email', 'boss@example.com')->count())->toBe(1)
        ->and($admin->fresh()->isAdmin())->toBeTrue()
        ->and($admin->fresh()->hasRole(UserRole::Editor->value))->toBeFalse();

    $this->assertAuthenticatedAs($admin);
});
