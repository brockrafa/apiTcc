<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('despesas_recorrentes', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('fornecedor')->nullable();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->decimal('valor', 10, 2);
            $table->unsignedTinyInteger('dia_vencimento'); // 1–31
            $table->boolean('ativa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despesas_recorrentes');
    }
};
