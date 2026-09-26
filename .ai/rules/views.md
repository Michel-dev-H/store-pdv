---
paths:
  - 'resources/views/**'
---

# Views

## Flux e componentes Blade primeiro; extraia só no 2º uso
DRY: prefira <flux:*> e os componentes Blade existentes (resources/views/components/*) antes de HTML novo. YAGNI: só extraia um componente novo quando o mesmo trecho aparecer pela segunda vez.

## money-input: entrada R$ amigável, valor interno em centavos
money-input recebe entrada amigável em R$, digita da direita para esquerda, exibe valor formatado e sincroniza centavos (int) com o Livewire. A string formatada nunca entra diretamente em cálculos financeiros — o backend trabalha com int.
