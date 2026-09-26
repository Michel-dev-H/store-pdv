@php use App\Support\Money; @endphp
<div>
    <flux:heading size="xl" class="mb-6">{{ __('PDV') }}</flux:heading>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <section class="space-y-4">
            <flux:field>
                <flux:label sr-only>{{ __('Buscar produtos') }}</flux:label>
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    icon="magnifying-glass"
                    :placeholder="__('Buscar produtos...')"
                />
            </flux:field>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @forelse ($this->products as $product)
                    <flux:card class="flex flex-col gap-4" wire:key="product-{{ $product->id }}">
                        <div class="flex flex-col gap-1">
                            <flux:heading size="sm">{{ $product->name }}</flux:heading>
                            <flux:text variant="subtle">{{ Money::toBRL($product->price) }}</flux:text>
                        </div>

                        <flux:button
                            variant="subtle"
                            icon="plus"
                            wire:click="addItem({{ $product->id }})"
                            wire:loading.attr="disabled"
                            wire:target="addItem({{ $product->id }})"
                        >
                            {{ __('Adicionar') }}
                        </flux:button>
                    </flux:card>
                @empty
                    <flux:callout variant="subtle" icon="magnifying-glass" class="sm:col-span-2">
                        {{ __('Nenhum produto encontrado.') }}
                    </flux:callout>
                @endforelse
            </div>
        </section>

        <section>
            <flux:card class="p-6">
                <flux:heading size="lg" class="mb-4">{{ __('Carrinho') }}</flux:heading>

                @forelse ($items as $productId => $item)
                    <div wire:key="item-{{ $productId }}" class="flex items-center justify-between gap-4 border-b border-line py-3 last:border-b-0">
                        <div class="min-w-0 space-y-0.5">
                            <flux:text weight="medium" class="truncate">{{ $item['name'] }}</flux:text>
                            <flux:text variant="subtle" size="sm">{{ Money::toBRL($item['unit_price']) }}</flux:text>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="minus"
                                wire:click="decrement({{ $productId }})"
                                aria-label="{{ __('Diminuir quantidade') }}"
                            />

                            <flux:text class="w-8 text-center" data-test="quantity">{{ $item['quantity'] }}</flux:text>

                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="plus"
                                wire:click="increment({{ $productId }})"
                                aria-label="{{ __('Aumentar quantidade') }}"
                            />

                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="trash"
                                icon:variant="outline"
                                class="text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50"
                                wire:click="removeItem({{ $productId }})"
                                aria-label="{{ __('Remover item') }}"
                            />
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-2 py-8 text-center">
                        <flux:icon.shopping-cart class="size-8 text-muted" />
                        <flux:text variant="subtle">{{ __('O carrinho está vazio.') }}</flux:text>
                    </div>
                @endforelse

                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <x-money-input
                        name="delivery_fee"
                        action="updateDelivery"
                        :value="$deliveryFee"
                        :label="__('Entrega')"
                        :placeholder="__('0,00')"
                    />

                    <flux:field>
                        <flux:label>{{ __('Desconto (%)') }}</flux:label>
                        <flux:input
                            type="number"
                            min="0"
                            max="100"
                            x-on:change="$wire.$call('updateDiscount', Number($el.value) || 0)"
                            :value="$discountPercent"
                        />
                        <flux:error name="discount_percent" />
                    </flux:field>
                </div>

                <dl class="mt-6 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt>{{ __('Subtotal') }}</dt>
                        <dd>{{ Money::toBRL($this->subtotal) }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt>{{ __('Desconto') }}</dt>
                        <dd data-test="discount">{{ Money::toBRL($this->discount) }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt>{{ __('Entrega') }}</dt>
                        <dd>{{ Money::toBRL($deliveryFee) }}</dd>
                    </div>

                    <div class="mt-2 flex justify-between border-t border-line pt-3 text-base font-semibold">
                        <dt>{{ __('Total') }}</dt>
                        <dd data-test="total">{{ Money::toBRL($this->total) }}</dd>
                    </div>
                </dl>

                <flux:button
                    variant="primary"
                    class="mt-6 w-full"
                    icon="check"
                    wire:click="finalize"
                    wire:loading.attr="disabled"
                    :disabled="empty($items)"
                >
                    {{ __('Finalizar venda') }}
                </flux:button>
            </flux:card>
        </section>
    </div>

    <flux:error name="product_id" />
    <flux:error name="quantity" />
    <flux:error name="items" />
</div>