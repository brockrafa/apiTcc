<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\ProdutoVenda;

class VendaController extends Controller
{
    public function index()
    {
        return response()->json(Venda::with(['cliente:id,nome','formaPagamento:id,descricao'])->select('id','cliente_id','data_venda','total','forma_pagamento_id')->get(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $existeCliente = Cliente::where('id', $request->cliente_id)->exists();

        if (!$existeCliente) {
            return response()->json([
                'errors' => 'Cliente com id: ' . $request->cliente_id . ' não existe.'
            ], Response::HTTP_NOT_FOUND);
        }

        $venda = new Venda();
        $venda->cliente_id = $request->cliente_id;
        $venda->total = 0;
        $venda->forma_pagamento_id = $request->forma_pagamento['id'];
        $venda->data_venda = now(); 
        $venda->save(); 

        foreach ($request->get('produtos') as $produto) {
            $produtoExiste = Produto::find($produto['id']);
            if (!$produtoExiste) {
                return response()->json([
                    'errors' => 'Produto com id: ' . $produto['id'] . ' não existe.'
                ], Response::HTTP_NOT_FOUND);
            }
            $prod = new ProdutoVenda();
            $prod->venda_id = $venda->id;
            $prod->produto_id = $produto['id'];
            $prod->quantidade = $produto['quantidade'];
            $prod->valor_unitario = $produto['valor'];
            $prod->save();
            $venda->total += $produto['quantidade'] * $produto['valor'];
        }
        $venda->save();
        return response()->json($venda, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $venda = Venda::with(['cliente:id,nome','formaPagamento:id,descricao','produtos'])->find($id);
        if (!$venda) {
            return response()->json([
                'errors' => 'Venda com id ' . $id . ' não existe.'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json($venda, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $venda)
    {
        $venda = Venda::find($venda);
        if (!$venda) {
            return response()->json([
                'errors' => 'Venda com id ' . $venda . ' não existe.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'errors' => 'teste'
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $venda = Venda::find($id);
        if (!$venda) {
            return response()->json([
                'errors' => 'Venda com id ' . $id . ' não existe.'
            ], Response::HTTP_NOT_FOUND);
        }
        $venda->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
