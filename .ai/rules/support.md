---
paths:
  - 'app/Support/**'
---

# Support

## Money: representação única em centavos (int)
Toda regra financeira do PDV usa a mesma representação (inteiros em centavos); nunca float. Desconto 0–100% com arredondamento half-up definido e testado; proibir valores negativos onde a regra de negócio não permite.
