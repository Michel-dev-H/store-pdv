---
paths:
  - 'tests/**'
---

# Tests

## TDD no fluxo: teste vermelho primeiro, factories nos testes
TDD: escreva o teste que falha antes da implementação; feature tests em Pest com User::factory() e seus estados (unverified, withTwoFactor) em vez de setup manual. Rode o conjunto estreito (php artisan test --compact --filter=...) e rode vendor/bin/pint --dirty e phpstan antes de finalizar.
