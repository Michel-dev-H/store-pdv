<?php

namespace App\Support;

use InvalidArgumentException;

class Money
{
    public static function toInt(string $input): int
    {
        $value = trim($input);

        if (! preg_match('/^\d{1,3}(\.\d{3})*(,\d{1,2})?$/', $value)) {
            throw new InvalidArgumentException("Valor monetário inválido: {$input}.");
        }

        [$reais, $centavos] = array_pad(explode(',', $value), 2, '');

        $reais = (int) str_replace('.', '', $reais);
        $centavos = (int) str_pad($centavos, 2, '0', STR_PAD_LEFT);

        return $reais * 100 + $centavos;
    }

    public static function toBRL(int $cents): string
    {
        if ($cents < 0) {
            throw new InvalidArgumentException('Valor monetário não pode ser negativo.');
        }

        $reais = intdiv($cents, 100);
        $centavos = $cents % 100;

        return sprintf('%s,%02d', number_format($reais, 0, ',', '.'), $centavos);
    }

    public static function applyDiscount(int $subtotal, int $percent): int
    {
        if ($subtotal < 0) {
            throw new InvalidArgumentException('Subtotal não pode ser negativo.');
        }

        if ($percent < 0 || $percent > 100) {
            throw new InvalidArgumentException('Percentual de desconto deve estar entre 0 e 100.');
        }

        $discount = intdiv($subtotal * $percent + 50, 100);

        return $subtotal - $discount;
    }
}
