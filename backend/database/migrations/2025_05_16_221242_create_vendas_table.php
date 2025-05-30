<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('vendas', function (Blueprint $table) {
         $table->id('vecodigo');
         $table->foreignId('clicodigo')->constrained('clientes', 'clicodigo');
         $table->foreignId('usucodigo')->constrained('usuarios', 'usucodigo');
         $table->foreignId('fpcodigo')->constrained('formas_pagamento', 'fpcodigo');
         $table->smallInteger('venumero_parcelas')->nullable();
         $table->decimal('vedesconto', 10, 2)->nullable();
         $table->decimal('vevalor_total', 10, 2);
         $table->smallInteger('vesituacao')->default(1)->comment('1 - Aberto, 2 - Finalizada, 3 - Cancelada');
         $table->date('vedata_hora_venda')->default(now());
      });
   }

   public function down(): void {
      Schema::dropIfExists('vendas');
   }

};