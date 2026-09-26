<?php

use App\Livewire\Pos;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->userWithPermission = function (array $permissions): User {
        app(RolesAndPermissionsSeeder::class)->run();

        $user = User::factory()->create();
        $user->givePermissionTo($permissions);

        return $user;
    };
});

it('starts with an empty cart and zeroed values', function () {
    $component = Livewire::test(Pos::class);

    $component
        ->assertSet('items', [])
        ->assertSet('deliveryFee', 0)
        ->assertSet('discountPercent', 0);
});

it('adds a product to the cart', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    $component = Livewire::test(Pos::class)
        ->call('addItem', $product->id);

    $component
        ->assertHasNoErrors()
        ->assertSet('items.'.$product->id.'.product_id', $product->id)
        ->assertSet('items.'.$product->id.'.name', 'Café')
        ->assertSet('items.'.$product->id.'.unit_price', 1500)
        ->assertSet('items.'.$product->id.'.quantity', 1);
});

it('increments the quantity of an existing item when the product is added again', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('addItem', $product->id)
        ->assertSet('items.'.$product->id.'.quantity', 2);
});

it('increments an item quantity', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('increment', $product->id)
        ->assertSet('items.'.$product->id.'.quantity', 2);
});

it('decrements an item quantity', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('increment', $product->id)
        ->call('decrement', $product->id)
        ->assertSet('items.'.$product->id.'.quantity', 1);
});

it('removes the item when its quantity is decremented to zero', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('decrement', $product->id)
        ->assertSet('items', []);
});

it('removes an item from the cart', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('removeItem', $product->id)
        ->assertSet('items', []);
});

it('sets an item quantity', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('setQuantity', $product->id, 3)
        ->assertHasNoErrors()
        ->assertSet('items.'.$product->id.'.quantity', 3);
});

it('rejects a quantity below one', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('setQuantity', $product->id, 0)
        ->assertHasErrors(['quantity'])
        ->assertSet('items.'.$product->id.'.quantity', 1);
});

it('rejects adding a nonexistent product', function () {
    Livewire::test(Pos::class)
        ->call('addItem', 999_999)
        ->assertHasErrors(['product_id'])
        ->assertSet('items', []);
});

it('calculates the subtotal', function () {
    $cafe = Product::factory()->create(['name' => 'Café', 'price' => 1500]);
    $arroz = Product::factory()->create(['name' => 'Arroz', 'price' => 2500]);

    Livewire::test(Pos::class)
        ->call('addItem', $cafe->id)
        ->call('addItem', $arroz->id)
        ->assertSee('40,00');
});

it('keeps the subtotal unchanged with a zero discount', function () {
    $cafe = Product::factory()->create(['name' => 'Café', 'price' => 1500]);
    $arroz = Product::factory()->create(['name' => 'Arroz', 'price' => 2500]);

    Livewire::test(Pos::class)
        ->call('addItem', $cafe->id)
        ->call('addItem', $arroz->id)
        ->call('updateDiscount', 0)
        ->assertSee('40,00')
        ->assertSee('0,00');
});

it('applies a partial discount', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    $component = Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('updateDiscount', 10);

    $component
        ->assertHasNoErrors()
        ->assertSee('13,50');
});

it('applies a full discount', function () {
    $cafe = Product::factory()->create(['name' => 'Café', 'price' => 1500]);
    $arroz = Product::factory()->create(['name' => 'Arroz', 'price' => 2500]);

    Livewire::test(Pos::class)
        ->call('addItem', $cafe->id)
        ->call('addItem', $arroz->id)
        ->call('updateDiscount', 100)
        ->assertSee('0,00');
});

it('keeps the total equal to the subtotal with no delivery fee', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('updateDelivery', 0)
        ->assertSee('15,00');
});

it('adds a delivery fee to the total', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('updateDelivery', 500)
        ->assertHasNoErrors()
        ->assertSee('20,00');
});

it('combines discount and delivery fee in the total', function () {
    $cafe = Product::factory()->create(['name' => 'Café', 'price' => 1500]);
    $arroz = Product::factory()->create(['name' => 'Arroz', 'price' => 2500]);

    Livewire::test(Pos::class)
        ->call('addItem', $cafe->id)
        ->call('addItem', $arroz->id)
        ->call('updateDiscount', 10)
        ->call('updateDelivery', 500)
        ->assertSee('40,00')
        ->assertSee('4,00')
        ->assertSee('41,00');
});

it('rejects a discount below zero', function () {
    Livewire::test(Pos::class)
        ->call('updateDiscount', -5)
        ->assertHasErrors(['discount_percent'])
        ->assertSet('discountPercent', 0);
});

it('rejects a discount above one hundred', function () {
    Livewire::test(Pos::class)
        ->call('updateDiscount', 120)
        ->assertHasErrors(['discount_percent'])
        ->assertSet('discountPercent', 0);
});

it('rejects a negative delivery fee', function () {
    Livewire::test(Pos::class)
        ->call('updateDelivery', -100)
        ->assertHasErrors(['delivery_fee'])
        ->assertSet('deliveryFee', 0);
});

it('keeps the total non-negative after a one hundred percent discount and delivery', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::test(Pos::class)
        ->call('addItem', $product->id)
        ->call('updateDiscount', 100)
        ->call('updateDelivery', 500)
        ->assertSee('5,00');
});

it('filters the catalog by search', function () {
    Product::factory()->create(['name' => 'Café especial']);
    Product::factory()->create(['name' => 'Arroz integral']);

    Livewire::test(Pos::class)
        ->set('search', 'café')
        ->assertSee('Café especial')
        ->assertDontSee('Arroz integral');
});

it('redirects guests away from the pdv page', function () {
    $this->get('/pdv')->assertRedirect(route('login'));
});

it('blocks users without permission from the pdv page', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/pdv')
        ->assertForbidden();
});

it('grants access to users with the pdv permission', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user)
        ->get('/pdv')
        ->assertOk();
});

it('blocks finalization for users without the sales.finalize permission', function () {
    $user = ($this->userWithPermission)(['pdv.access']);

    Livewire::actingAs($user)
        ->test(Pos::class)
        ->call('addItem', Product::factory()->create()->id)
        ->call('finalize')
        ->assertStatus(403);
});

it('allows an authorized user to finalize a sale', function () {
    $user = ($this->userWithPermission)(['sales.finalize']);
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::actingAs($user)
        ->test(Pos::class)
        ->call('addItem', $product->id)
        ->call('finalize')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('sales', 1);
});

it('rejects finalizing an empty cart', function () {
    $user = ($this->userWithPermission)(['sales.finalize']);

    Livewire::actingAs($user)
        ->test(Pos::class)
        ->call('finalize')
        ->assertHasErrors(['items']);

    $this->assertDatabaseCount('sales', 0);
});

it('persists the sale with the correct totals', function () {
    $user = ($this->userWithPermission)(['sales.finalize']);
    $cafe = Product::factory()->create(['name' => 'Café', 'price' => 1500]);
    $arroz = Product::factory()->create(['name' => 'Arroz', 'price' => 2500]);

    Livewire::actingAs($user)
        ->test(Pos::class)
        ->call('addItem', $cafe->id)
        ->call('addItem', $arroz->id)
        ->call('updateDiscount', 10)
        ->call('updateDelivery', 500)
        ->call('finalize')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sales', [
        'user_id' => $user->id,
        'subtotal' => 4000,
        'discount' => 400,
        'delivery_fee' => 500,
        'total' => 4100,
    ]);
});

it('persists a sale item snapshot for each cart line', function () {
    $user = ($this->userWithPermission)(['sales.finalize']);
    $cafe = Product::factory()->create(['name' => 'Café', 'price' => 1500]);
    $arroz = Product::factory()->create(['name' => 'Arroz', 'price' => 2500]);

    Livewire::actingAs($user)
        ->test(Pos::class)
        ->call('addItem', $cafe->id)
        ->call('addItem', $arroz->id)
        ->call('addItem', $cafe->id)
        ->call('finalize')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('sale_items', 2);
    $this->assertDatabaseHas('sale_items', [
        'product_name' => 'Café',
        'unit_price' => 1500,
        'quantity' => 2,
        'line_total' => 3000,
    ]);
    $this->assertDatabaseHas('sale_items', [
        'product_name' => 'Arroz',
        'unit_price' => 2500,
        'quantity' => 1,
        'line_total' => 2500,
    ]);
});

it('finalizes with the current price from the database, not the cart state', function () {
    $user = ($this->userWithPermission)(['sales.finalize']);
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    $component = Livewire::actingAs($user)->test(Pos::class);
    $component->call('addItem', $product->id);

    $product->update(['price' => 2000]);

    $component->call('finalize')->assertHasNoErrors();

    $this->assertDatabaseHas('sales', ['total' => 2000]);
    $this->assertDatabaseHas('sale_items', ['unit_price' => 2000, 'line_total' => 2000]);
});

it('clears the cart and resets values after a successful finalization', function () {
    $user = ($this->userWithPermission)(['sales.finalize']);
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    Livewire::actingAs($user)
        ->test(Pos::class)
        ->call('addItem', $product->id)
        ->call('updateDiscount', 10)
        ->call('updateDelivery', 500)
        ->call('finalize')
        ->assertHasNoErrors()
        ->assertSet('items', [])
        ->assertSet('discountPercent', 0)
        ->assertSet('deliveryFee', 0);
});

it('blocks finalization when a product was deleted after being added', function () {
    $user = ($this->userWithPermission)(['sales.finalize']);
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    $component = Livewire::actingAs($user)->test(Pos::class);
    $component->call('addItem', $product->id);

    $product->delete();

    $component->call('finalize')
        ->assertHasErrors(['items.'.$product->id]);

    $this->assertDatabaseCount('sales', 0);
});

it('does not persist a partial sale when the transaction fails', function () {
    $user = ($this->userWithPermission)(['sales.finalize']);
    $product = Product::factory()->create(['name' => 'Café', 'price' => 1500]);

    DB::listen(function ($query) {
        if (str_contains($query->sql, 'insert') && str_contains($query->sql, 'sale_items')) {
            throw new RuntimeException('forced failure');
        }
    });

    $component = Livewire::actingAs($user)->test(Pos::class);
    $component->call('addItem', $product->id);

    try {
        $component->call('finalize');
    } catch (RuntimeException) {
        // a falha é esperada e força o rollback da transação
    }

    $this->assertDatabaseCount('sales', 0);
    $this->assertDatabaseCount('sale_items', 0);

    $component->assertSet('items.'.$product->id.'.quantity', 1);
});
