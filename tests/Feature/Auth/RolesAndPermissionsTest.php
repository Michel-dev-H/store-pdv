<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('creates the expected roles', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $roles = Role::query()->pluck('name')->sort()->values()->all();

    expect($roles)->toBe(['admin', 'user']);
});

it('creates the expected permissions', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $permissions = Permission::query()->pluck('name')->sort()->values()->all();

    expect($permissions)->toBe(['pdv.access', 'sales.finalize', 'sales.view', 'sales.view-any']);
});

it('grants the user role the base permissions', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('user');

    expect($user->hasPermissionTo('pdv.access'))->toBeTrue()
        ->and($user->hasPermissionTo('sales.view'))->toBeTrue()
        ->and($user->hasPermissionTo('sales.finalize'))->toBeTrue()
        ->and($user->hasPermissionTo('sales.view-any'))->toBeFalse();
});

it('grants the admin role every permission', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    foreach (['pdv.access', 'sales.view', 'sales.finalize', 'sales.view-any'] as $permission) {
        expect($admin->hasPermissionTo($permission))->toBeTrue();
    }
});

it('assigns the user role automatically on registration', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = app(CreatesNewUsers::class)->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    expect($user->hasRole('user'))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeFalse();
});

it('creates a demo admin in the database seeder', function () {
    $this->seed();

    $admin = User::query()->where('email', 'admin@example.com')->first();

    expect($admin)->not->toBeNull()
        ->and($admin->hasRole('admin'))->toBeTrue()
        ->and($admin->hasPermissionTo('sales.view-any'))->toBeTrue();
});

it('is idempotent and does not duplicate roles and permissions when run again', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(RolesAndPermissionsSeeder::class);

    expect(Role::query()->count())->toBe(2)
        ->and(Permission::query()->count())->toBe(4);
});

it('enforces permissions through the gate', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create();
    $admin = User::factory()->create();
    $user->assignRole('user');
    $admin->assignRole('admin');

    expect(Gate::forUser($user)->allows('sales.finalize'))->toBeTrue()
        ->and(Gate::forUser($user)->allows('sales.view-any'))->toBeFalse()
        ->and(Gate::forUser($admin)->allows('sales.view-any'))->toBeTrue();
});
