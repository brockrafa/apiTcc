<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClienteController extends Controller
{
    private array $validacaoPadrao = [
        'nome' => 'required|string|min:4',
        'telefone' => 'required|string',
        'email' => 'nullable|email'
    ];

    public function index()
    {
        return response()->json(Cliente::all(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {   
        $request->validate($this->validacaoPadrao,$this->messages);
        $cliente = Cliente::create($request->all());
        return response()->json($cliente, Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        try{
            $cliente = Cliente::findOrFail($id);
            return response()->json($cliente, Response::HTTP_OK);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'errors' =>  'Cliente com id:'. $id. ' não existe.'], Response::HTTP_NOT_FOUND
            ); 
        }
    }

    public function update(Request $request, string $id)
    {
        try{
            $cliente = Cliente::findOrFail($id);
            $request->validate($this->validacaoPadrao,$this->messages);
            $cliente->update($request->all());
            return response()->noContent();
        }catch(ModelNotFoundException $e){
            return response()->json([
                'errors' => ['id' => 'Cliente com id:'. $id. ' não encontrado.']], Response::HTTP_NOT_FOUND
            ); 
        }
    }

    public function destroy(string $id)
    {
        Cliente::findOrFail($id)->delete();
        return response()->noContent();
    }
}
