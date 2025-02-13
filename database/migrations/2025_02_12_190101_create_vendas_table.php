<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("cliente_id");
            $table->unsignedBigInteger("forma_pagamento_id")->nullable();
            $table->decimal("total", 10, 2);
            $table->date("data_venda");
            $table->foreign("cliente_id")->references("id")->on("clientes");
            $table->foreign("forma_pagamento_id")->references("id")->on("forma_pagamentos");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
