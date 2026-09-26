<?php

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds around ten demo products', function () {
    $this->seed(ProductSeeder::class);

    expect(Product::count())->toBeGreaterThanOrEqual(10);
});

it('is idempotent and does not duplicate products when run again', function () {
    $this->seed(ProductSeeder::class);
    $firstRun = Product::count();

    $this->seed(ProductSeeder::class);

    expect(Product::count())->toBe($firstRun);
});

it('is reproducible and predictable', function () {
    $this->seed(ProductSeeder::class);

    $names = Product::query()->pluck('name')->unique()->values();

    $this->seed(ProductSeeder::class);

    expect(Product::query()->pluck('name')->unique()->values())->toEqual($names);
});

it('stores prices as integers in cents', function () {
    $this->seed(ProductSeeder::class);

    foreach (Product::all() as $product) {
        expect($product->price)->toBeInt();
    }
});
