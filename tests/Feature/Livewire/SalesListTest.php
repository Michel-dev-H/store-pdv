<?php

use App\Livewire\SalesList;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function actingUserWithPermissions(array $permissions): User
{
    app(RolesAndPermissionsSeeder::class)->run();

    $user = User::factory()->create();
    $user->givePermissionTo($permissions);

    return $user;
}

function createSaleFor(User $user, array $overrides = []): Sale
{
    $sale = Sale::factory()->for($user)->create(array_merge([
        'subtotal' => 3400,
        'discount' => 400,
        'delivery_fee' => 500,
        'total' => 3500,
    ], $overrides));

    $sale->saleItems()->createMany([
        [
            'product_id' => Product::factory()->create(['name' => 'Café Arábica'])->id,
            'product_name' => 'Café Arábica',
            'unit_price' => 1200,
            'quantity' => 2,
            'line_total' => 2400,
        ],
        [
            'product_id' => Product::factory()->create(['name' => 'Bolo de Chocolate'])->id,
            'product_name' => 'Bolo de Chocolate',
            'unit_price' => 1000,
            'quantity' => 1,
            'line_total' => 1000,
        ],
    ]);

    return $sale;
}

it('blocks guests from accessing the sales history page', function () {
    $this->get(route('sales'))->assertRedirect(route('login'));
});

it('blocks users without the sales.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('sales'))->assertForbidden();
});

it('shows only the authenticated user sales', function () {
    $user = actingUserWithPermissions(['sales.view']);
    createSaleFor($user, ['total' => 9900]);

    $otherUser = User::factory()->create();
    createSaleFor($otherUser, ['total' => 5500]);

    $component = Livewire::actingAs($user)->test(SalesList::class);

    $component
        ->assertSee('99,00')
        ->assertDontSee('55,00');
});

it('shows every sale to admins', function () {
    $admin = actingUserWithPermissions(['sales.view', 'sales.view-any']);
    createSaleFor($admin, ['total' => 2400]);

    $otherUser = User::factory()->create();
    createSaleFor($otherUser, ['total' => 7700]);

    $component = Livewire::actingAs($admin)->test(SalesList::class);

    $component
        ->assertSee('24,00')
        ->assertSee('77,00');
});

it('displays the seller name column only for admins', function () {
    $admin = actingUserWithPermissions(['sales.view', 'sales.view-any']);
    createSaleFor($admin);

    Livewire::actingAs($admin)->test(SalesList::class)
        ->assertSee('Vendedor')
        ->assertSee($admin->name);

    $user = actingUserWithPermissions(['sales.view']);
    createSaleFor($user);

    Livewire::actingAs($user)->test(SalesList::class)
        ->assertDontSee('Vendedor')
        ->assertDontSee($admin->name);
});

it('shows the formatted financial values', function () {
    $user = actingUserWithPermissions(['sales.view']);
    createSaleFor($user);

    $component = Livewire::actingAs($user)->test(SalesList::class);

    $component
        ->assertSee('34,00')
        ->assertSee('4,00')
        ->assertSee('5,00')
        ->assertSee('35,00');
});

it('opens the modal with the sale items', function () {
    $user = actingUserWithPermissions(['sales.view']);
    $sale = createSaleFor($user);

    $component = Livewire::actingAs($user)->test(SalesList::class)
        ->call('openItems', $sale->id)
        ->assertSet('selectedSaleId', $sale->id);

    $component
        ->assertSee('Café Arábica')
        ->assertSee('Bolo de Chocolate')
        ->assertSee('12,00')
        ->assertSee('10,00')
        ->assertSee('2')
        ->assertSee('24,00');
});

it('blocks a user from opening another user sale items', function () {
    $user = actingUserWithPermissions(['sales.view']);
    $otherSale = createSaleFor(User::factory()->create());

    Livewire::actingAs($user)
        ->test(SalesList::class)
        ->call('openItems', $otherSale->id)
        ->assertStatus(403);
});

it('paginates the sales list', function () {
    $user = actingUserWithPermissions(['sales.view']);

    Sale::factory()->count(12)->for($user)->create();

    $component = Livewire::actingAs($user)->test(SalesList::class);

    $component
        ->assertViewHas('sales', fn ($sales) => $sales->count() === 10)
        ->call('gotoPage', 2)
        ->assertViewHas('sales', fn ($sales) => $sales->count() === 2);
});

it('shows an empty state when there are no sales', function () {
    $user = actingUserWithPermissions(['sales.view']);

    Livewire::actingAs($user)->test(SalesList::class)
        ->assertSee('Nenhuma venda encontrada');
});

it('does not load sale items while listing the sales', function () {
    $user = actingUserWithPermissions(['sales.view']);

    Sale::factory()->count(10)->for($user)->create();

    DB::enableQueryLog();

    Livewire::actingAs($user)->test(SalesList::class);

    $itemQueries = collect(DB::getQueryLog())->filter(
        fn ($query) => str_contains($query['query'], 'sale_items')
    );

    expect($itemQueries)->toHaveCount(0);
});
