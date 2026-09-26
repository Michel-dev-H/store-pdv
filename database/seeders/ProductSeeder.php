<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Products used for demonstration, with prices in cents.
     *
     * @var array<int, array{name: string, price: int}>
     */
    private const PRODUCTS = [
        ['name' => 'Café Torrado Moído 500g', 'price' => 1890],
        ['name' => 'Arroz Tipo 1 5kg', 'price' => 2490],
        ['name' => 'Feijão Carioca 1kg', 'price' => 799],
        ['name' => 'Açúcar Demerara 1kg', 'price' => 799],
        ['name' => 'Óleo de Soja 900ml', 'price' => 649],
        ['name' => 'Leite Integral 1L', 'price' => 489],
        ['name' => 'Farinha de Trigo 1kg', 'price' => 599],
        ['name' => 'Sal Refinado 1kg', 'price' => 299],
        ['name' => 'Macarrão Espaguete 500g', 'price' => 449],
        ['name' => 'Molho de Tomate 340g', 'price' => 399],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (self::PRODUCTS as $product) {
            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}
