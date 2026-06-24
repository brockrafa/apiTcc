<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\LancamentoFinanceiroController;

Route::resource('clientes', ClienteController::class);
Route::resource('produtos', ProdutoController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('servicos', ServicoController::class);
Route::resource('vendas', VendaController::class);
Route::resource('forma-pagamento', FormaPagamentoController::class);
Route::resource('lancamentos-financeiros', LancamentoFinanceiroController::class);
Route::patch('/lancamentos-financeiros/{id}/baixar', [LancamentoFinanceiroController::class, 'baixar']);



