<?php

namespace App\Models;

use App\Models\Concerns\HasEmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory, HasEmpresaScope;

    protected $fillable = ['nome','email','telefone','cep','bairro','logradouro','cidade','empresa_id'];
}
