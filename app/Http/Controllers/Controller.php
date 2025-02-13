<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $messages = [
        'required' => 'O campo :attribute é obrigatório',
        'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
        'email' => 'É necessário informar um email válido'
    ];
}
