<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('creates the products table with the expected schema', function () {
    expect(Schema::hasTable('products'))->toBeTrue();

    foreach (['id', 'name', 'price', 'created_at', 'updated_at'] as $column) {
        expect(Schema::hasColumn('products', $column))->toBeTrue();
    }

    expect(Schema::getColumns('products')[0]['nullable'])->toBeFalse();
});

it('stores a product with an integer price in cents', function () {
    $product = Product::factory()->create(['price' => 123456]);

    expect($product->getRawOriginal('price'))->toBe(123456);
});

it('casts the price to an integer', function () {
    $product = Product::factory()->create(['price' => 1590]);

    expect($product->price)->toBe(1590)
        ->and($product->price)->toBeInt();
});
