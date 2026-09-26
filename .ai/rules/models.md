---
paths:
  - 'app/Models/**'
---

# Models

## Snapshots históricos; venda não depende do produto atual
sale_items guarda snapshot da venda (product_name, unit_price, quantity, line_total); product_id nullable com onDelete SET NULL. Editar ou deletar um produto não altera vendas antigas.
