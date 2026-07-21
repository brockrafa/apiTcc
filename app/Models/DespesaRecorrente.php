<?php
namespace App\Models;
use App\Models\Concerns\HasEmpresaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DespesaRecorrente extends Model
{
    use HasFactory, HasEmpresaScope;

    protected $table = 'despesas_recorrentes';

    protected $fillable = [
        'descricao', 'fornecedor', 'categoria_id',
        'valor', 'dia_vencimento', 'ativa', 'empresa_id',
    ];

    protected $casts = [
        'ativa'          => 'boolean',
        'valor'          => 'decimal:2',
        'dia_vencimento' => 'integer',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function contasPagar()
    {
        return $this->hasMany(ContaPagar::class);
    }
}