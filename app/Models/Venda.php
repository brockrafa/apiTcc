<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LancamentoFinanceiro;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    use HasFactory;
    protected $fillable = ['cliente_id', 'total', 'forma_pagamento', 'data_venda', 'tipo_venda','parcelas','entrada','valor_parcela','primeiro_vencimento'];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'item_vendas')
            ->select('produtos.id', 'produtos.produto', 'produtos.valor', 'produtos.valorVenda')
            ->withPivot('quantidade', 'valor_unitario','tipo');
    }

    public function itens()
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }
    

    public function lancamentos(): HasMany
    {
        return $this->hasMany(LancamentoFinanceiro::class);
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function formaPagamento(){
        return $this->belongsTo(FormaPagamento::class);
    }
}
