<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Sale;
use App\Support\Money;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('PDV')]
class Pos extends Component
{
    /**
     * @var array<int, array{
     *     product_id: int,
     *     name: string,
     *     unit_price: int,
     *     quantity: int
     * }>
     */
    public array $items = [];

    public int $deliveryFee = 0;

    public int $discountPercent = 0;

    public string $search = '';

    public function addItem(int $productId): void
    {
        $product = Product::find($productId);

        if (! $product) {
            $this->addError('product_id', 'Produto não encontrado.');

            return;
        }

        if (isset($this->items[$productId])) {
            $this->increment($productId);

            return;
        }

        $this->items[$productId] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_price' => $product->price,
            'quantity' => 1,
        ];
    }

    public function increment(int $productId): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        $this->items[$productId]['quantity']++;
    }

    public function decrement(int $productId): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        if ($this->items[$productId]['quantity'] <= 1) {
            unset($this->items[$productId]);

            return;
        }

        $this->items[$productId]['quantity']--;
    }

    public function removeItem(int $productId): void
    {
        unset($this->items[$productId]);
    }

    public function setQuantity(int $productId, int $quantity): void
    {
        if (! isset($this->items[$productId])) {
            return;
        }

        if ($quantity < 1) {
            $this->addError('quantity', 'A quantidade deve ser no mínimo 1.');

            return;
        }

        $this->items[$productId]['quantity'] = $quantity;
    }

    public function updateDelivery(int $deliveryFee): void
    {
        if ($deliveryFee < 0) {
            $this->addError('delivery_fee', 'A taxa de entrega não pode ser negativa.');

            return;
        }

        $this->deliveryFee = $deliveryFee;
    }

    public function updateDiscount(int $discountPercent): void
    {
        if ($discountPercent < 0 || $discountPercent > 100) {
            $this->addError('discount_percent', 'O desconto deve estar entre 0 e 100%.');

            return;
        }

        $this->discountPercent = $discountPercent;
    }

    public function finalize(): void
    {
        $this->authorize('sales.finalize');

        if ($this->items === []) {
            $this->addError('items', 'O carrinho está vazio.');

            return;
        }

        $productIds = array_keys($this->items);

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        foreach ($productIds as $productId) {
            if (! isset($products[$productId])) {
                $this->addError("items.{$productId}", 'Produto não encontrado.');

                return;
            }
        }

        DB::transaction(function () use ($products) {
            $saleItems = [];
            $subtotal = 0;

            foreach ($this->items as $productId => $item) {
                $product = $products[$productId];
                $lineTotal = $product->price * $item['quantity'];
                $subtotal += $lineTotal;

                $saleItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal,
                ];
            }

            $discounted = Money::applyDiscount($subtotal, $this->discountPercent);

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount' => $subtotal - $discounted,
                'delivery_fee' => $this->deliveryFee,
                'total' => $discounted + $this->deliveryFee,
            ]);

            $sale->saleItems()->createMany($saleItems);
        });

        $this->reset('items', 'deliveryFee', 'discountPercent');

        Flux::toast(variant: 'success', text: 'Venda finalizada com sucesso.');
    }

    #[Computed]
    public function subtotal(): int
    {
        $subtotal = 0;

        foreach ($this->items as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
        }

        return $subtotal;
    }

    #[Computed]
    public function discounted(): int
    {
        return Money::applyDiscount($this->subtotal(), $this->discountPercent);
    }

    #[Computed]
    public function discount(): int
    {
        return $this->subtotal() - $this->discounted;
    }

    #[Computed]
    public function total(): int
    {
        return $this->discounted + $this->deliveryFee;
    }

    #[Computed]
    public function products(): Collection
    {
        return Product::query()
            ->when($this->search !== '', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->get();
    }
}
