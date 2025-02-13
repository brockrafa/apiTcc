<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoriaController extends Controller
{
    private $validacaoPadrao = [
        'nome' => 'required|min:4'
    ];

    public function index()
    {
        return response()->json(Categoria::all(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {   
        $request->validate($this->validacaoPadrao,$this->messages);

        $categoria = Categoria::create($request->all());

        return response()->json($categoria, Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoria não encontrado',
                'errors' => ['id' => 'Categoria com ID:'. $id. ' não existe.']
            ], Response::HTTP_NOT_FOUND); 
        }

        return response()->json($categoria, Response::HTTP_OK); 
    }

    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);
        if (!$categoria) {
            return response()->json([
                'message' => 'Categoria não encontrado',
                'errors' => ['id' => 'Categoria com ID:'. $id. ' não existe.']
            ], Response::HTTP_NOT_FOUND);
        }
        $request->validate($this->validacaoPadrao,$this->messages);
        $categoria->update($request->all());
        return response()->noContent();
    }

    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'message' => 'Categoria não encontrado',
                'errors' => ['id' => 'Categoria com ID:'. $id. ' não existe.']
            ], Response::HTTP_NOT_FOUND);  
        }
        $categoria->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT); 
    }
}
