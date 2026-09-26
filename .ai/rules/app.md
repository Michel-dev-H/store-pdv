---
paths:
  - 'app/**'
---

# App

## Sem abstrações especulativas; siga o layout do starter kit
YAGNI: não crie Services/Repositories/DTOs ou grandes abstrações até existir um segundo consumidor real — use Eloquent + componentes Livewire. Siga a estrutura existente (Models, Livewire, Actions/Fortify, Concerns). DRY: reutilize as traits de validação já existentes (App\Concerns\*) em vez de repetir regras.

## Autorização real no backend (Spatie); papel user por padrão
Papéis e permissões com spatie/laravel-permission; bloquear a ação no backend — esconder botões não basta. Registro atribui o papel user automaticamente, sem confiar em dados enviados pelo frontend.

## Inspecionar antes de modificar; não assumir comportamento
Antes de editar, verificar estrutura, convenções, funcionalidades existentes, migrations, rotas, componentes, autenticação, testes e dependências instaladas. Em dúvida sobre API/versão/integração/comportamento financeiro, conferir a documentação da versão instalada ou testes — não inventar solução como fato.

## Escopo controlado: classificar melhorias
Melhorias espontâneas durante a implementação são classificadas como "necessária para o escopo" ou "evolução futura". Não implementar uma evolução futura automaticamente.
