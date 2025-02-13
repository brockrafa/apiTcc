<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;
    protected $fillable = ['cliente_id', 'total', 'forma_pagamento', 'data_venda'];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'produto_vendas')->withPivot('quantidade', 'valor_unitario');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function formaPagamento(){
        return $this->belongsTo(FormaPagamento::class);
    }
}
