<?php

namespace App\Models;

use App\Models\Concerns\HasEmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    use HasFactory, HasEmpresaScope;
    public $timestamps = false;
    protected $fillable = ['venda_id', 'produto_id','servico_id','quantidade', 'valor_unitario','empresa_id'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
    
}
