<?php

use App\Support\Money;
use InvalidArgumentException;

it('converts a plain integer input to cents', function () {
    expect(Money::toInt('10'))->toBe(1000);
});

it('converts a value with decimal separator to cents', function () {
    expect(Money::toInt('15,90'))->toBe(1590);
});

it('converts a value with thousand separator to cents', function () {
    expect(Money::toInt('1.234,56'))->toBe(123456);
});

it('converts zero to zero cents', function () {
    expect(Money::toInt('0'))->toBe(0);
});

it('rejects invalid monetary inputs', function (string $input) {
    Money::toInt($input);
})->with([
    '',
    'abc',
    '-10',
    '12,345',
    '1.2345',
    '10,',
])->throws(InvalidArgumentException::class);

it('formats cents as a brazilian real string', function () {
    expect(Money::toBRL(123456))->toBe('1.234,56');
});

it('formats zero cents', function () {
    expect(Money::toBRL(0))->toBe('0,00');
});

it('formats amounts below one real', function () {
    expect(Money::toBRL(90))->toBe('0,90');
});

it('keeps the subtotal unchanged with a 0 percent discount', function () {
    expect(Money::applyDiscount(10000, 0))->toBe(10000);
});

it('discounts the full subtotal with a 100 percent discount', function () {
    expect(Money::applyDiscount(10000, 100))->toBe(0);
});

it('applies a percentage discount rounding half up', function () {
    expect(Money::applyDiscount(1055, 7))->toBe(981);
    expect(Money::applyDiscount(250, 5))->toBe(237);
});

it('rejects a discount percent above 100', function () {
    Money::applyDiscount(10000, 101);
})->throws(InvalidArgumentException::class);

it('rejects a negative discount percent', function () {
    Money::applyDiscount(10000, -1);
})->throws(InvalidArgumentException::class);

it('rejects a negative subtotal', function () {
    Money::applyDiscount(-1, 10);
})->throws(InvalidArgumentException::class);
