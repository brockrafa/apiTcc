<?php

namespace App\Http\Controllers;

use App\Models\LancamentoFinanceiro;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LancamentoFinanceiroController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->query('mes', now()->format('Y-m'));

        $lancamentos = LancamentoFinanceiro::with('cliente:id,nome', 'venda:id,data_venda')
            ->whereRaw("DATE_FORMAT(data_vencimento, '%Y-%m') = ?", [$mes])
            ->orderBy('data_vencimento')
            ->get();

        // Atualiza automaticamente para "atrasado" os pendentes vencidos
        $lancamentos->each(function ($lancamento) {
            if ($lancamento->status === 'pendente' && $lancamento->data_vencimento->isPast()) {
                $lancamento->update(['status' => 'atrasado']);
                $lancamento->status = 'atrasado';
            }
        });

        return response()->json($lancamentos, Response::HTTP_OK);
    }

    public function show($id)
    {
        $lancamento = LancamentoFinanceiro::with('cliente:id,nome', 'venda:id,data_venda')->find($id);

        if (!$lancamento) {
            return response()->json([
                'errors' => "Lançamento com id {$id} não existe."
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($lancamento, Response::HTTP_OK);
    }

    public function baixar($id)
    {
        $lancamento = LancamentoFinanceiro::find($id);

        if (!$lancamento) {
            return response()->json([
                'errors' => "Lançamento com id {$id} não existe."
            ], Response::HTTP_NOT_FOUND);
        }

        if ($lancamento->status === 'pago') {
            return response()->json([
                'errors' => 'Este lançamento já foi baixado.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $lancamento->update([
            'status'          => 'pago',
            'data_pagamento'  => now()->toDateString(),
        ]);

        return response()->json($lancamento, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $lancamento = LancamentoFinanceiro::find($id);

        if (!$lancamento) {
            return response()->json([
                'errors' => "Lançamento com id {$id} não existe."
            ], Response::HTTP_NOT_FOUND);
        }

        if ($lancamento->status === 'pago') {
            return response()->json([
                'errors' => 'Não é possível excluir um lançamento já pago.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $lancamento->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function resumo(Request $request)
    {
        $mes = $request->query('mes', now()->format('Y-m'));

        $dados = LancamentoFinanceiro::whereRaw("DATE_FORMAT(data_vencimento, '%Y-%m') = ?", [$mes])
            ->select('status', DB::raw('COUNT(*) as quantidade'), DB::raw('SUM(valor) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return response()->json([
            'pendente' => [
                'quantidade' => $dados['pendente']->quantidade ?? 0,
                'total'      => $dados['pendente']->total ?? 0,
            ],
            'pago' => [
                'quantidade' => $dados['pago']->quantidade ?? 0,
                'total'      => $dados['pago']->total ?? 0,
            ],
            'atrasado' => [
                'quantidade' => $dados['atrasado']->quantidade ?? 0,
                'total'      => $dados['atrasado']->total ?? 0,
            ],
        ], Response::HTTP_OK);
    }
}
