<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\LancamentoFinanceiroReceberController;
use App\Http\Controllers\LancamentoFinanceiroPagarController;
use App\Http\Controllers\ContaPagarController;
use App\Http\Controllers\DespesaRecorrenteController;

Route::resource('clientes', ClienteController::class);
Route::resource('produtos', ProdutoController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('servicos', ServicoController::class);
Route::resource('vendas', VendaController::class);
Route::resource('forma-pagamento', FormaPagamentoController::class);
Route::resource('lancamentos-financeiros', LancamentoFinanceiroReceberController::class);
Route::patch('/lancamentos-financeiros/{id}/baixar', [LancamentoFinanceiroReceberController::class, 'baixar']);
Route::patch('/lancamentos-financeiros/{id}/baixar-com-valor',
    [LancamentoFinanceiroReceberController::class, 'baixarComValor']);

Route::get('/lancamentos/contas-pagar',                             [LancamentoFinanceiroPagarController::class, 'index']);
Route::post('/lancamentos-financeiros/lancar-recorrentes-pagar',    [LancamentoFinanceiroPagarController::class, 'lancarRecorrentes']);
Route::patch('/contas-pagar/{id}/pagar',                            [LancamentoFinanceiroPagarController::class, 'pagar']);
Route::delete('/contas-pagar/{id}',                                 [LancamentoFinanceiroPagarController::class, 'destroy']);
Route::delete('/contas-pagar/{id}',                                 [LancamentoFinanceiroPagarController::class, 'destroy']);
Route::put('/contas-pagar/{id}',                                    [LancamentoFinanceiroPagarController::class, 'update']);


// ── Contas a Pagar ────────────────────────────────────────
Route::get('/contas-pagar/resumo',                [ContaPagarController::class, 'resumo']);
Route::post('/contas-pagar/lancar-recorrentes',   [ContaPagarController::class, 'lancarRecorrentes']);
Route::get('/contas-pagar',                       [ContaPagarController::class, 'index']);
Route::post('/contas-pagar',                      [ContaPagarController::class, 'store']);


// ── Despesas Recorrentes ──────────────────────────────────
Route::get('/despesas-recorrentes',               [DespesaRecorrenteController::class, 'index']);
Route::post('/despesas-recorrentes',              [DespesaRecorrenteController::class, 'store']);
Route::put('/despesas-recorrentes/{id}',          [DespesaRecorrenteController::class, 'update']);
Route::delete('/despesas-recorrentes/{id}',       [DespesaRecorrenteController::class, 'destroy']);
Route::get('/despesas-recorrentes/{id}',       [DespesaRecorrenteController::class, 'show']);

