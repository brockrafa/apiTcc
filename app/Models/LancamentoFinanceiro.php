<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LancamentoFinanceiro extends Model
{
    use HasFactory;

    protected $table = 'lancamentos_financeiros';

    protected $fillable = [
        'venda_id',
        'cliente_id',
        'numero_parcela',
        'total_parcelas',
        'valor',
        'valor_pago',
        'data_vencimento',
        'data_pagamento',
        'status',
        'tipo',
        'descricao',
        'fornecedor',
        'categoria_id',
        'forma_pagamento',
        'conta_pagar_id',
        'observacao'
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento'  => 'date',
        'valor'           => 'decimal:2',
    ];

    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
