<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto',
        'estoque',
        'valor',
        'valorVenda',
        'categoria'
    ];

    public function vendas()
    {
        return $this->belongsToMany(Venda::class, 'produto_vendas');
    }
    
}
