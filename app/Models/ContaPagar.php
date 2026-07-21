<?php

namespace App\Models;
use App\Models\Concerns\HasEmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaPagar extends Model
{
    use HasFactory, SoftDeletes, HasEmpresaScope;

    protected $table = 'contas_pagar';

    protected $fillable = [
        'despesa_recorrente_id', 'categoria_id',
        'descricao', 'fornecedor',
        'valor', 'data_vencimento',
        'tipo', 'numero_parcela', 'total_parcelas',
        'observacao', 'status',
        'valor_pago', 'data_pagamento',
        'forma_pagamento', 'observacao_pagamento',
        'empresa_id',
    ];

    protected $casts = [
        'valor'           => 'decimal:2',
        'valor_pago'      => 'decimal:2',
        'data_vencimento' => 'date',
        'data_pagamento'  => 'date',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function despesaRecorrente()
    {
        return $this->belongsTo(DespesaRecorrente::class);
    }

    public function lancamentoFinanceiro()
    {
        return $this->hasOne(LancamentoFinanceiro::class, 'conta_pagar_id');
    }
}
