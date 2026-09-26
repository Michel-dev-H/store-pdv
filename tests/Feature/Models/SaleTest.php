<?php

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('creates the sales table with the expected schema', function () {
    expect(Schema::hasTable('sales'))->toBeTrue();

    foreach (['id', 'user_id', 'subtotal', 'discount', 'delivery_fee', 'total', 'created_at', 'updated_at'] as $column) {
        expect(Schema::hasColumn('sales', $column))->toBeTrue();
    }
});

it('belongs to a user', function () {
    $user = User::factory()->create();
    $sale = Sale::factory()->create(['user_id' => $user->id]);

    expect($sale->user->is($user))->toBeTrue();
});

it('has many sale items', function () {
    $sale = Sale::factory()->create();
    $item = SaleItem::factory()->create(['sale_id' => $sale->id, 'product_id' => null]);

    expect($sale->saleItems->contains($item))->toBeTrue();
});

it('casts financial columns to integers', function () {
    $sale = Sale::factory()->create([
        'subtotal' => 1000,
        'discount' => 100,
        'delivery_fee' => 250,
        'total' => 1150,
    ]);

    expect($sale->subtotal)->toBeInt()
        ->and($sale->discount)->toBeInt()
        ->and($sale->delivery_fee)->toBeInt()
        ->and($sale->total)->toBeInt();
});

it('recalculates nothing and persists the amounts as given', function () {
    $sale = Sale::factory()->create([
        'subtotal' => 1000,
        'discount' => 100,
        'delivery_fee' => 250,
        'total' => 1150,
    ]);

    expect([
        'subtotal' => $sale->getRawOriginal('subtotal'),
        'discount' => $sale->getRawOriginal('discount'),
        'delivery_fee' => $sale->getRawOriginal('delivery_fee'),
        'total' => $sale->getRawOriginal('total'),
    ])->toBe([
        'subtotal' => 1000,
        'discount' => 100,
        'delivery_fee' => 250,
        'total' => 1150,
    ]);
});
