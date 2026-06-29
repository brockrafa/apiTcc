<?php

namespace App\Http\Controllers;

use App\Models\ContaPagar;
use App\Models\DespesaRecorrente;
use App\Models\LancamentoFinanceiro;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContaPagarController extends Controller
{
    /**
     * Lista as contas a pagar do mês solicitado.
     * Marca automaticamente como "atrasado" as pendentes vencidas.
     *
     * GET /contas-pagar?mes=2025-06
     */
    public function index(Request $request)
    {
        $mes = $request->query('mes', now()->format('Y-m'));

        $contas = ContaPagar::with('categoria:id,nome')
            ->whereRaw("DATE_FORMAT(data_vencimento, '%Y-%m') = ?", [$mes])
            ->orderBy('data_vencimento')
            ->get();

        $contas->each(function ($conta) {
            if ($conta->status === 'pendente' && Carbon::parse($conta->data_vencimento)->isPast()) {
                $conta->update(['status' => 'atrasado']);
                $conta->status = 'atrasado';
            }
        });

        return response()->json($contas, Response::HTTP_OK);
    }

    /**
     * Cadastra uma nova conta a pagar.
     * Se tipo = "parcelada", cria N lançamentos mensais a partir do vencimento.
     *
     * POST /contas-pagar
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'descricao'       => 'required|string|max:255',
            'fornecedor'      => 'nullable|string|max:255',
            'categoria_id'    => 'nullable|exists:categorias,id',
            'valor'           => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date_format:Y-m-d',
            'tipo'            => 'required|in:fixa,variavel,parcelada,eventual',
            'parcelas'        => 'nullable|integer|min:2',
            'observacao'      => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            if (($data['tipo'] ?? '') === 'parcelada' && ($data['parcelas'] ?? 1) > 1) {
                $parcelas = (int) $data['parcelas'];
                $base = Carbon::parse($data['data_vencimento']);

                for ($i = 0; $i < $parcelas; $i++) {
                    ContaPagar::create([
                        ...$data,
                        'data_vencimento'   => $base->copy()->addMonths($i)->format('Y-m-d'),
                        'numero_parcela'    => $i + 1,
                        'total_parcelas'    => $parcelas,
                        'status'            => 'pendente',
                    ]);
                }
            } else {
                ContaPagar::create([
                    ...$data,
                    'numero_parcela' => 1,
                    'total_parcelas' => 1,
                    'status'         => 'pendente',
                ]);
            }
        });

        return response()->json(['message' => 'Conta(s) cadastrada(s) com sucesso.'], Response::HTTP_CREATED);
    }

    /**
     * Atualiza uma conta a pagar (apenas campos editáveis, não altera status).
     *
     * PUT /contas-pagar/{id}
     */
    public function update(Request $request, $id)
    {
        $conta = ContaPagar::find($id);

        if (!$conta) {
            return response()->json(['errors' => "Conta com id {$id} não existe."], Response::HTTP_NOT_FOUND);
        }

        if ($conta->status === 'pago') {
            return response()->json(['errors' => 'Não é possível editar uma conta já paga.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->validate([
            'descricao'       => 'sometimes|required|string|max:255',
            'fornecedor'      => 'nullable|string|max:255',
            'categoria_id'    => 'nullable|exists:categorias,id',
            'valor'           => 'sometimes|required|numeric|min:0.01',
            'data_vencimento' => 'sometimes|required|date_format:Y-m-d',
            'tipo'            => 'sometimes|required|in:fixa,variavel,parcelada,eventual',
            'observacao'      => 'nullable|string',
        ]);

        $conta->update($data);

        return response()->json($conta, Response::HTTP_OK);
    }

    /**
     * Remove uma conta a pagar (somente se não estiver paga).
     *
     * DELETE /contas-pagar/{id}
     */
    public function destroy($id)
    {
        $conta = ContaPagar::find($id);

        if (!$conta) {
            return response()->json(['errors' => "Conta com id {$id} não existe."], Response::HTTP_NOT_FOUND);
        }

        if ($conta->status === 'pago') {
            return response()->json(['errors' => 'Não é possível excluir uma conta já paga.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $conta->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Registra o pagamento de uma conta.
     *
     * Cria automaticamente um LancamentoFinanceiro de saída (tipo = 'saida')
     * para que o fluxo de caixa e dashboards reflitam a despesa.
     *
     * PATCH /contas-pagar/{id}/pagar
     *
     * Body JSON:
     *   valor_pago       float   – valor efetivamente pago (pode diferir do registrado)
     *   data_pagamento   string  – 'YYYY-MM-DD'
     *   forma_pagamento  string  – 'pix' | 'dinheiro' | 'transferencia' | 'boleto' | 'cartao_debito' | 'cartao_credito'
     *   observacao       string? – detalhes do pagamento
     */
    public function pagar(Request $request, $id)
    {
        $request->validate([
            'valor_pago'      => 'required|numeric|min:0.01',
            'data_pagamento'  => 'required|date_format:Y-m-d',
            'forma_pagamento' => 'required|in:pix,dinheiro,transferencia,boleto,cartao_debito,cartao_credito',
            'observacao'      => 'nullable|string',
        ]);

        $conta = ContaPagar::find($id);

        if (!$conta) {
            return response()->json(['errors' => "Conta com id {$id} não existe."], Response::HTTP_NOT_FOUND);
        }

        if ($conta->status === 'pago') {
            return response()->json(['errors' => 'Esta conta já foi paga.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::transaction(function () use ($conta, $request) {
            // 1. Atualiza a conta
            $conta->update([
                'status'          => 'pago',
                'valor_pago'      => $request->valor_pago,
                'data_pagamento'  => $request->data_pagamento,
                'forma_pagamento' => $request->forma_pagamento,
                'observacao_pagamento' => $request->observacao,
            ]);

            // 2. Cria lançamento financeiro de saída para o fluxo de caixa
            LancamentoFinanceiro::create([
                'tipo'            => 'saida',
                'descricao'       => $conta->descricao,
                'valor'           => $request->valor_pago,
                'data_vencimento' => $conta->data_vencimento,
                'data_pagamento'  => $request->data_pagamento,
                'status'          => 'pago',
                'categoria_id'    => $conta->categoria_id,
                'fornecedor'      => $conta->fornecedor,
                'forma_pagamento' => $request->forma_pagamento,
                'conta_pagar_id'  => $conta->id,
                'observacao'      => $request->observacao,
            ]);
        });

        $conta->refresh();
        return response()->json($conta, Response::HTTP_OK);
    }

    /**
     * Lança automaticamente todas as despesas recorrentes ativas
     * que ainda não possuem entrada no mês solicitado.
     *
     * POST /contas-pagar/lancar-recorrentes
     *
     * Body JSON:
     *   mes  string  – 'YYYY-MM'
     */
    public function lancarRecorrentes(Request $request)
    {
        $request->validate([
            'mes' => 'required|date_format:Y-m',
        ]);

        $mes = $request->mes;
        [$ano, $numMes] = explode('-', $mes);

        // IDs de recorrentes já lançadas neste mês
        $jaLancados = ContaPagar::where('despesa_recorrente_id', '!=', null)
            ->whereRaw("DATE_FORMAT(data_vencimento, '%Y-%m') = ?", [$mes])
            ->pluck('despesa_recorrente_id')
            ->toArray();

        $recorrentes = DespesaRecorrente::where('ativa', true)
            ->whereNotIn('id', $jaLancados)
            ->get();

        $lancadas = 0;

        DB::transaction(function () use ($recorrentes, $ano, $numMes, &$lancadas) {
            foreach ($recorrentes as $rec) {
                // Calcula o dia de vencimento respeitando o último dia do mês
                $dia   = min((int) $rec->dia_vencimento, Carbon::create($ano, $numMes, 1)->daysInMonth);
                $venc  = Carbon::create($ano, $numMes, $dia)->format('Y-m-d');

                ContaPagar::create([
                    'descricao'            => $rec->descricao,
                    'fornecedor'           => $rec->fornecedor,
                    'categoria_id'         => $rec->categoria_id,
                    'valor'                => $rec->valor,
                    'data_vencimento'      => $venc,
                    'tipo'                 => 'fixa',
                    'numero_parcela'       => 1,
                    'total_parcelas'       => 1,
                    'status'               => 'pendente',
                    'despesa_recorrente_id' => $rec->id,
                ]);

                $lancadas++;
            }
        });

        return response()->json(['lancadas' => $lancadas], Response::HTTP_OK);
    }

    /**
     * Resumo do mês para dashboards de fluxo de caixa.
     *
     * GET /contas-pagar/resumo?mes=2025-06
     */
    public function resumo(Request $request)
    {
        $mes = $request->query('mes', now()->format('Y-m'));

        $dados = ContaPagar::whereRaw("DATE_FORMAT(data_vencimento, '%Y-%m') = ?", [$mes])
            ->select('status', DB::raw('COUNT(*) as quantidade'), DB::raw('SUM(valor) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return response()->json([
            'pendente' => ['quantidade' => $dados['pendente']->quantidade ?? 0, 'total' => $dados['pendente']->total ?? 0],
            'pago'     => ['quantidade' => $dados['pago']->quantidade     ?? 0, 'total' => $dados['pago']->total     ?? 0],
            'atrasado' => ['quantidade' => $dados['atrasado']->quantidade ?? 0, 'total' => $dados['atrasado']->total ?? 0],
        ], Response::HTTP_OK);
    }
}
