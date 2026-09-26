<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the landing page', function () {
    $this->get(route('home'))->assertOk();
});

it('contains the three landing sections', function () {
    $response = $this->get(route('home'));

    $response
        ->assertSee('id="inicio"', false)
        ->assertSee('id="produtos"', false)
        ->assertSee('id="contato"', false);
});

it('shows the login link to guests', function () {
    $this->get(route('home'))
        ->assertSee(route('login'))
        ->assertSee('Login');
});

it('shows the dashboard link to authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('home'))
        ->assertSee(route('pdv'))
        ->assertSee('Dashboard');
});

it('points the navbar anchors to the correct sections', function () {
    $response = $this->get(route('home'));

    $response
        ->assertSee('href="#inicio"', false)
        ->assertSee('href="#produtos"', false)
        ->assertSee('href="#contato"', false);
});

it('renders the contact form inside the contato section', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('wire:submit="submit"', false)
        ->assertSee('wire:model="name"', false)
        ->assertSee('wire:model="email"', false)
        ->assertSee('wire:model="phone"', false)
        ->assertSee('wire:model="message"', false);
});

it('contains the artisanal details from the design', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Fornadas quentes às 07h e 16h')
        ->assertSee('Fermentação lenta de 36 horas')
        ->assertSee('Artesania Pura')
        ->assertSee('Farinhas Selecionadas & Leveduras Selvagens')
        ->assertSee('EST. 2021')
        ->assertSee('LOTE Nº 48')
        ->assertSee('100% Orgânico')
        ->assertSee('Retornamos seu contato em até poucas horas.')
        ->assertSee('Seus dados estão protegidos. Respeitamos seu tempo e privacidade.');
});

it('contains the products section from the design screen', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Nossos destaques')
        ->assertSee('todos os dias.')
        ->assertSee('Receitas desenvolvidas com paciência, ingredientes limpos e o respeito ao tempo natural da fermentação.')
        ->assertSee('Blends selecionados e métodos que valorizam o grão.')
        ->assertSee('Pães de fermentação natural e bolos da casa, feitos todas as manhãs.')
        ->assertSee('Cestas prontas para levar o melhor da padaria para sua mesa.')
        ->assertSee('Ver cardápio completo')
        ->assertSee('Ingredientes 100% naturais')
        ->assertSee('Fornos acesos a partir das 06:00')
        ->assertSee('LOTE LIMITADO DIÁRIO');
});

it('contains the contato section from the design screen', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Venha tomar um café passado na hora, sentir o cheirinho de pão quente ou mande uma mensagem para encomendar fornadas especiais.')
        ->assertSee('Rua das Laranjeiras, 142 — Centro')
        ->assertSee('ola@graoecia.com.br')
        ->assertSee('(11) 99999-9999')
        ->assertSee('Terça a Domingo, das 07h às 19h')
        ->assertSee('Feito com afeto artesanal.');
});
