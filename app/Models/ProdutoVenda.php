<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoVenda extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['venda_id', 'produto_id', 'quantidade', 'valor_unitario'];
}
