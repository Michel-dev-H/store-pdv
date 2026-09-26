<?php

use App\Livewire\Pos;
use App\Livewire\SalesList;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('pdv', Pos::class)
        ->middleware('can:pdv.access')
        ->name('pdv');

    Route::livewire('vendas', SalesList::class)
        ->middleware('can:sales.view')
        ->name('sales');
});

require __DIR__.'/settings.php';
