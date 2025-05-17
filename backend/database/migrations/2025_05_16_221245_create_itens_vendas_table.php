<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('itens_vendas', function (Blueprint $table) {
         $table->id('ivcodigo');
         $table->foreignId('vecodigo')->constrained('vendas', 'vecodigo');
         $table->foreignId('procodigo')->constrained('produtos', 'procodigo');
         $table->smallInteger('ivquantidade');
         $table->decimal('ivpreco_unitario', 10, 2);
         $table->decimal('ivsubtotal', 10, 2);
      });
   }

   public function down(): void {
      Schema::dropIfExists('itens_vendas');
   }

};