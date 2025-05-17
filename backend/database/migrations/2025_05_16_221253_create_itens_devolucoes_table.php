<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('itens_devolucoes', function (Blueprint $table) {
         $table->id('idcodigo');
         $table->foreignId('decodigo')->constrained('devolucoes', 'decodigo');
         $table->foreignId('ivcodigo')->constrained('itens_vendas', 'ivcodigo');
         $table->smallInteger('idquantidade');
         $table->decimal('idsubtotal', 10, 2);
      });
   }

   public function down(): void{
      Schema::dropIfExists('itens_devolucoes');
   }

};