<?php

namespace App\Livewire;

use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Vendas')]
class SalesList extends Component
{
    use WithPagination;

    public int $selectedSaleId = 0;

    public function render(): View
    {
        return view('livewire.sales-list', [
            'sales' => $this->sales(),
        ]);
    }

    /**
     * @return LengthAwarePaginator<int, Sale>
     */
    public function sales(): LengthAwarePaginator
    {
        return Sale::query()
            ->when(
                $this->seesAllSales(),
                fn ($query) => $query->with('user:id,name'),
                fn ($query) => $query->where('user_id', auth()->id()),
            )
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function openItems(int $saleId): void
    {
        $sale = Sale::findOrFail($saleId);

        abort_unless($this->canViewSale($sale), 403);

        $this->selectedSaleId = $sale->id;

        $this->dispatch('sale-items-open');
    }

    private function seesAllSales(): bool
    {
        return auth()->user()->can('sales.view-any');
    }

    private function canViewSale(Sale $sale): bool
    {
        return $this->seesAllSales() || $sale->user_id === auth()->id();
    }

    #[Computed]
    public function selectedSale(): ?Sale
    {
        if ($this->selectedSaleId === 0) {
            return null;
        }

        return Sale::with('saleItems')->find($this->selectedSaleId);
    }
}
