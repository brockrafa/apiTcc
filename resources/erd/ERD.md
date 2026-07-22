## Diagrama MER (Mermaid)

```mermaid
erDiagram
    EMPRESA {
        int id PK
        string nome
        string cnpj
        string contrato_ref
    }

    USER {
        int id PK
        int empresa_id FK
        string name
        string email
        string password
        boolean ativo
    }

    CLIENTE {
        int id PK
        int empresa_id FK
        string nome
        string email
        string telefone
        string cep
        string bairro
        string logradouro
        string cidade
    }

    CATEGORIA {
        int id PK
        int empresa_id FK
        string nome
    }

    PRODUTO {
        int id PK
        int empresa_id FK
        string produto
        int estoque
        decimal valor
        decimal valor_venda
        string categoria
    }

    SERVICO {
        int id PK
        int empresa_id FK
        string servico
        decimal valor
        string categoria
    }

    VENDA {
        int id PK
        int empresa_id FK
        int cliente_id FK
        decimal total
        int forma_pagamento FK
        date data_venda
        string tipo_venda
        int parcelas
        decimal entrada
        decimal valor_parcela
        date primeiro_vencimento
    }

    ITEM_VENDA {
        int id PK
        int venda_id FK
        int produto_id FK
        int servico_id FK
        int quantidade
        decimal valor_unitario
        int empresa_id FK
    }

    FORMA_PAGAMENTO {
        int id PK
        string descricao
    }

    LANCAMENTO_FINANCEIRO {
        int id PK
        int empresa_id FK
        int venda_id FK
        int cliente_id FK
        int numero_parcela
        int total_parcelas
        decimal valor
        decimal valor_pago
        date data_vencimento
        date data_pagamento
        string status
        string tipo
        text descricao
        string fornecedor
        int categoria_id FK
        int forma_pagamento FK
        int conta_pagar_id FK
    }

    DESPESA_RECORRENTE {
        int id PK
        int empresa_id FK
        string descricao
        string fornecedor
        int categoria_id FK
        decimal valor
        int dia_vencimento
        boolean ativa
    }

    CONTA_PAGAR {
        int id PK
        int empresa_id FK
        int despesa_recorrente_id FK
        int categoria_id FK
        string descricao
        string fornecedor
        decimal valor
        date data_vencimento
        string tipo
        int numero_parcela
        int total_parcelas
        decimal valor_pago
        date data_pagamento
        int forma_pagamento FK
    }

    EMPRESA ||--o{ USER : "tem"
    EMPRESA ||--o{ CLIENTE : "tem"
    EMPRESA ||--o{ CATEGORIA : "tem"
    EMPRESA ||--o{ PRODUTO : "tem"
    EMPRESA ||--o{ SERVICO : "tem"
    EMPRESA ||--o{ VENDA : "tem"
    EMPRESA ||--o{ LANCAMENTO_FINANCEIRO : "tem"
    EMPRESA ||--o{ DESPESA_RECORRENTE : "tem"
    EMPRESA ||--o{ CONTA_PAGAR : "tem"

    CLIENTE ||--o{ VENDA : "realiza"

    VENDA ||--o{ ITEM_VENDA : "contém"
    PRODUTO ||--o{ ITEM_VENDA : "aparece em"
    SERVICO ||--o{ ITEM_VENDA : "aparece em"
    ITEM_VENDA }|..|{ VENDA : "pivot"
    ITEM_VENDA }|..|{ PRODUTO : "pivot"

    VENDA ||--o{ LANCAMENTO_FINANCEIRO : "gera"
    CLIENTE ||--o{ LANCAMENTO_FINANCEIRO : "possui"

    CONTA_PAGAR ||--|| LANCAMENTO_FINANCEIRO : "pode gerar"

    CATEGORIA ||--o{ DESPESA_RECORRENTE : "classifica"
    DESPESA_RECORRENTE ||--o{ CONTA_PAGAR : "gera"
    CATEGORIA ||--o{ CONTA_PAGAR : "classifica"

    FORMA_PAGAMENTO ||--o{ VENDA : "usada em"
    FORMA_PAGAMENTO ||--o{ LANCAMENTO_FINANCEIRO : "usada em"

```

Observações:
- Entidades e campos baseados em `app/Models/*` (`$fillable`).
- Campos FK inferidos a partir de relações `belongsTo` e nomes de campos (ex.: `cliente_id`, `empresa_id`).
- Se quiser, gero uma imagem PNG (PlantUML ou Mermaid) e salvo em `resources/erd/`.
