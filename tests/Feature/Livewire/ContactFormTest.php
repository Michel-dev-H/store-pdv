<?php

use App\Livewire\ContactForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('starts with empty fields and no success state', function () {
    Livewire::test(ContactForm::class)
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('phone', null)
        ->assertSet('message', '')
        ->assertSet('sent', false);
});

it('persists a message with valid data', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Maria Silva')
        ->set('email', 'maria@example.com')
        ->set('message', 'Gostaria de saber o horário de funcionamento.')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('sent', true);

    $this->assertDatabaseCount('contact_messages', 1);
    $this->assertDatabaseHas('contact_messages', [
        'name' => 'Maria Silva',
        'email' => 'maria@example.com',
        'message' => 'Gostaria de saber o horário de funcionamento.',
    ]);
});

it('persists a message with a phone number', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Maria Silva')
        ->set('email', 'maria@example.com')
        ->set('phone', '(11) 99999-9999')
        ->set('message', 'Gostaria de saber o horário de funcionamento.')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('sent', true)
        ->assertSet('phone', null);

    $this->assertDatabaseHas('contact_messages', [
        'name' => 'Maria Silva',
        'email' => 'maria@example.com',
        'phone' => '(11) 99999-9999',
        'message' => 'Gostaria de saber o horário de funcionamento.',
    ]);
});

it('resets the fields after a successful submission', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Maria Silva')
        ->set('email', 'maria@example.com')
        ->set('message', 'Gostaria de saber o horário de funcionamento.')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('message', '')
        ->assertSet('sent', true);
});

it('rejects an empty submission', function () {
    Livewire::test(ContactForm::class)
        ->call('submit')
        ->assertHasErrors(['name' => 'required', 'email' => 'required', 'message' => 'required'])
        ->assertSet('sent', false);
});

it('rejects an invalid email address', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Maria Silva')
        ->set('email', 'nao-e-um-email')
        ->set('message', 'Mensagem de teste.')
        ->call('submit')
        ->assertHasErrors(['email']);

    $this->assertDatabaseCount('contact_messages', 0);
});

it('rejects a message longer than the allowed length', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Maria Silva')
        ->set('email', 'maria@example.com')
        ->set('message', str_repeat('a', 2001))
        ->call('submit')
        ->assertHasErrors(['message']);

    $this->assertDatabaseCount('contact_messages', 0);
});
