<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public string $message = '';

    public bool $sent = false;

    /**
     * Store a contact message sent from the landing page.
     */
    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create($validated);

        $this->reset('name', 'email', 'phone', 'message');

        $this->sent = true;
    }

    public function render(): View
    {
        return view('livewire.contact-form');
    }
}
