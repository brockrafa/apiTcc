<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Http\Response;

class ProdutoController extends Controller
{
    // Validação padrão para a criação e atualização de produtos
    private $validacaoPadrao = [
        'produto' => 'required|min:4',
        'estoque' => 'required|integer',
        'valor' => 'required|numeric',
        'valorVenda' => 'required|numeric',
        'categoria' => 'required|integer'
    ];

    public function index()
    {
        return response()->json(Produto::all(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {   
        $request->validate($this->validacaoPadrao,$this->messages);

        $produto = Produto::create($request->all());

        return response()->json($produto, Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json([
                'message' => 'Produto não encontrado',
                'errors' => ['id' => 'Produto com ID:'. $id. ' não existe.']
            ], Response::HTTP_NOT_FOUND); 
        }

        return response()->json($produto, Response::HTTP_OK);  // Retorna o produto com status 200
    }

    public function update(Request $request, string $id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json([
                'message' => 'Produto não encontrado',
                'errors' => ['id' => 'Produto com ID:'. $id. ' não existe.']
            ], Response::HTTP_NOT_FOUND);
        }
        $request->validate($this->validacaoPadrao,$this->messages);
        $produto->update($request->all());
        return response()->noContent();
    }

    public function destroy(string $id)
    {
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json([
                'message' => 'Produto não encontrado',
                'errors' => ['id' => 'Produto com ID:'. $id. ' não existe.']
            ], Response::HTTP_NOT_FOUND);  
        }
        $produto->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT); 
    }
}
