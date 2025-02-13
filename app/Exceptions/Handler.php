<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // // Tratamento global para QueryException (erro no banco de dados)
        /*if ($exception instanceof QueryException) {
            return response()->json([
                'message'=>'Erro na conexão com banco de dados',
                'errors' => 'Ocorreu um erro na comunicação com bando de dados'
            ], 500);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $exception->validator->errors()->first()
            ], 422);
        }

        if ($exception instanceof Exception) {
            return response()->json([
                'errors' => $exception->getMessage()
            ], 500);
        }
*/
        // Outros tratamentos de exceções, se necessário
        return parent::render($request, $exception);
    }
}
