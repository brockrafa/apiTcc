<?php

namespace App\Http\Controllers;

use App\Models\DespesaRecorrente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DespesaRecorrenteController extends Controller
{
    /**
     * Lista todas as despesas recorrentes da empresa.
     *
     * GET /despesas-recorrentes
     */
    public function index()
    {
        $recorrentes = DespesaRecorrente::with('categoria:id,nome')
            ->orderBy('descricao')
            ->get();

        return response()->json($recorrentes, Response::HTTP_OK);
    }

    public function show($id)
    {
        $recorrente = DespesaRecorrente::with('categoria:id,nome')->find($id);

        if (!$recorrente) {
            return response()->json(['errors' => "Recorrente com id {$id} não existe."], Response::HTTP_NOT_FOUND);
        }

        return response()->json($recorrente, Response::HTTP_OK);
    }

    /**
     * Cadastra uma nova despesa recorrente.
     *
     * POST /despesas-recorrentes
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'descricao'      => 'required|string|max:255',
            'fornecedor'     => 'nullable|string|max:255',
            'categoria_id'   => 'nullable|exists:categorias,id',
            'valor'          => 'required|numeric|min:0.01',
            'dia_vencimento' => 'required|integer|min:1|max:31',
            'ativa'          => 'boolean',
        ]);

        $recorrente = DespesaRecorrente::create($data);

        return response()->json($recorrente, Response::HTTP_CREATED);
    }

    /**
     * Atualiza uma despesa recorrente.
     *
     * PUT /despesas-recorrentes/{id}
     */
    public function update(Request $request, $id)
    {
        $recorrente = DespesaRecorrente::find($id);

        if (!$recorrente) {
            return response()->json(['errors' => "Recorrente com id {$id} não existe."], Response::HTTP_NOT_FOUND);
        }

        $data = $request->validate([
            'descricao'      => 'sometimes|required|string|max:255',
            'fornecedor'     => 'nullable|string|max:255',
            'categoria_id'   => 'nullable|exists:categorias,id',
            'valor'          => 'sometimes|required|numeric|min:0.01',
            'dia_vencimento' => 'sometimes|required|integer|min:1|max:31',
            'ativa'          => 'boolean',
        ]);

        $recorrente->update($data);

        return response()->json($recorrente, Response::HTTP_OK);
    }

    /**
     * Remove uma despesa recorrente.
     * As contas já lançadas nos meses anteriores são mantidas.
     *
     * DELETE /despesas-recorrentes/{id}
     */
    public function destroy($id)
    {
        $recorrente = DespesaRecorrente::find($id);

        if (!$recorrente) {
            return response()->json(['errors' => "Recorrente com id {$id} não existe."], Response::HTTP_NOT_FOUND);
        }

        $recorrente->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
