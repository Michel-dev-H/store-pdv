@php use App\Support\Money; @endphp
<div>
    <flux:heading size="xl" class="mb-6">{{ __('Vendas') }}</flux:heading>

    @if ($sales->count())
        <flux:table :paginate="$sales">
            <flux:table.columns>
                <flux:table.column>{{ __('Data') }}</flux:table.column>

                @can('sales.view-any')
                    <flux:table.column>{{ __('Vendedor') }}</flux:table.column>
                @endcan

                <flux:table.column align="end">{{ __('Subtotal') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Desconto') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Entrega') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Total') }}</flux:table.column>
                <flux:table.column align="end"></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($sales as $sale)
                    <flux:table.row :key="$sale->id">
                        <flux:table.cell>{{ $sale->created_at->format('d/m/Y H:i') }}</flux:table.cell>

                        @can('sales.view-any')
                            <flux:table.cell variant="strong">{{ $sale->user->name }}</flux:table.cell>
                        @endcan

                        <flux:table.cell align="end">{{ Money::toBRL($sale->subtotal) }}</flux:table.cell>
                        <flux:table.cell align="end">{{ Money::toBRL($sale->discount) }}</flux:table.cell>
                        <flux:table.cell align="end">{{ Money::toBRL($sale->delivery_fee) }}</flux:table.cell>
                        <flux:table.cell align="end" variant="strong">{{ Money::toBRL($sale->total) }}</flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:button variant="subtle" size="sm" wire:click="openItems({{ $sale->id }})">
                                {{ __('Ver') }}
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    @else
        <flux:callout variant="subtle" icon="clipboard-document-list">
            {{ __('Nenhuma venda encontrada') }}
        </flux:callout>
    @endif

    <flux:modal name="sale-items" x-on:sale-items-open.window="$flux.modal('sale-items').show()">
        <flux:heading size="lg" class="mb-4">{{ __('Itens da venda') }}</flux:heading>

        @php($selectedSale = $this->selectedSale)

        @if ($selectedSale)
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Produto') }}</flux:table.column>
                    <flux:table.column>{{ __('Quantidade') }}</flux:table.column>
                    <flux:table.column align="end">{{ __('Preço unitário') }}</flux:table.column>
                    <flux:table.column align="end">{{ __('Total da linha') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($selectedSale->saleItems as $item)
                        <flux:table.row :key="$item->id">
                            <flux:table.cell variant="strong">{{ $item->product_name }}</flux:table.cell>
                            <flux:table.cell>{{ $item->quantity }}</flux:table.cell>
                            <flux:table.cell align="end">{{ Money::toBRL($item->unit_price) }}</flux:table.cell>
                            <flux:table.cell align="end">{{ Money::toBRL($item->line_total) }}</flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif
    </flux:modal>
</div>