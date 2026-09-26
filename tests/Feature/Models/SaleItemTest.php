<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('creates the sale_items table with the expected schema', function () {
    expect(Schema::hasTable('sale_items'))->toBeTrue();

    foreach (['id', 'sale_id', 'product_id', 'product_name', 'unit_price', 'quantity', 'line_total', 'created_at', 'updated_at'] as $column) {
        expect(Schema::hasColumn('sale_items', $column))->toBeTrue();
    }
});

it('allows a null product_id to preserve the historical snapshot', function () {
    $item = SaleItem::factory()->create([
        'sale_id' => Sale::factory(),
        'product_id' => null,
        'product_name' => 'Produto antigo',
        'unit_price' => 500,
        'quantity' => 2,
        'line_total' => 1000,
    ]);

    expect($item->product_id)->toBeNull();
});

it('belongs to a sale', function () {
    $sale = Sale::factory()->create();
    $item = SaleItem::factory()->create(['sale_id' => $sale->id, 'product_id' => null]);

    expect($item->sale->is($sale))->toBeTrue();
});

it('belongs to a product when the product still exists', function () {
    $product = Product::factory()->create();
    $item = SaleItem::factory()->create([
        'sale_id' => Sale::factory(),
        'product_id' => $product->id,
    ]);

    expect($item->product->is($product))->toBeTrue();
});

it('does not fail when the product has been removed', function () {
    $product = Product::factory()->create();
    $item = SaleItem::factory()->create([
        'sale_id' => Sale::factory(),
        'product_id' => $product->id,
    ]);

    $product->delete();
    $item->refresh();

    expect($item->product_id)->toBeNull()
        ->and($item->product)->toBeNull();
});

it('keeps the snapshot even after the product is deleted', function () {
    $product = Product::factory()->create(['name' => 'Café', 'price' => 890]);
    $item = SaleItem::factory()->create([
        'sale_id' => Sale::factory(),
        'product_id' => $product->id,
        'product_name' => $product->name,
        'unit_price' => $product->price,
        'quantity' => 2,
        'line_total' => 1780,
    ]);

    $product->delete();
    $item->refresh();

    expect($item->product_name)->toBe('Café')
        ->and($item->unit_price)->toBe(890)
        ->and($item->line_total)->toBe(1780);
});

it('casts financial columns to integers', function () {
    $item = SaleItem::factory()->create(['product_id' => null]);

    expect($item->unit_price)->toBeInt()
        ->and($item->quantity)->toBeInt()
        ->and($item->line_total)->toBeInt();
});
