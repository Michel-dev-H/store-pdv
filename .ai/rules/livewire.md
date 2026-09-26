---
paths:
  - 'app/Livewire/**'
---

# Livewire

## Componentes seguem as convenções de Settings; reutilize antes de criar
Espelhe o padrão dos componentes existentes (#[Title]/#[Computed], Flux::toast, traits de validação). DRY: aproveite componentes já existentes; não duplique lógica entre componentes.

## PDV: backend é a fonte da verdade; finalização transacional
Na finalização, recomprar os produtos no banco — nunca confiar em preços vindos do estado do componente. Calcular subtotal, desconto, taxa e total no backend. Criar Sale + SaleItems dentro de DB::transaction(); qualquer falha não grava venda parcial.

## Histórico de vendas: visibilidade na query, sem N+1
Filtrar visibilidade na consulta (usuário vê apenas as suas vendas; admin vê todas) — nunca filtrar só no Blade. Usar paginação Livewire carregando apenas os dados necessários e evitar N+1.
