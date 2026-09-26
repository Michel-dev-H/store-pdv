@props([
    'name' => null,
    'action' => null,
    'value' => 0,
    'label' => null,
    'placeholder' => '0,00',
    'maxDigits' => 9,
])

@php
    $cents = (int) $value;
@endphp

<div
    x-data="{
        cents: {{ $cents }},
        maxDigits: {{ $maxDigits }},
        format(cents) {
            let padded = String(cents).padStart(3, '0');
            let reais = padded.slice(0, -2);
            let centavos = padded.slice(-2);

            reais = (parseInt(reais, 10) || 0).toLocaleString('pt-BR');

            return reais + ',' + centavos;
        },
        onInput(event) {
            let digits = event.target.value.replace(/\D/g, '').slice(0, this.maxDigits);

            this.cents = digits === '' ? 0 : parseInt(digits, 10);
            event.target.value = this.format(this.cents);
        },
        commit() {
            $wire.$call(@js($action), this.cents);
        },
    }"
>
    <flux:field>
        @if ($label)
            <flux:label>{{ $label }}</flux:label>
        @endif

        <flux:input
            type="text"
            x-init="$el.value = format(cents)"
            x-on:input="onInput"
            x-on:change="commit"
            inputmode="numeric"
            autocomplete="off"
            :placeholder="$placeholder"
        />

        @if ($name)
            <flux:error :name="$name" />
        @endif
    </flux:field>
</div>