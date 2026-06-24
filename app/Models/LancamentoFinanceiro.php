<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LancamentoFinanceiro extends Model
{
    protected $table = 'lancamentos_financeiros';

    protected $fillable = [
        'venda_id',
        'cliente_id',
        'numero_parcela',
        'total_parcelas',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'status',
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
