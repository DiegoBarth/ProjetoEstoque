<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('devolucoes', function (Blueprint $table) {
         $table->id('decodigo');
         $table->foreignId('vecodigo')->constrained('vendas', 'vecodigo');
         $table->foreignId('usucodigo')->constrained('usuarios', 'usucodigo');
         $table->string('demotivo', 50)->nullable();
         $table->decimal('devalor_total', 10, 2)->nullable();
      });
   }

   public function down(): void {
      Schema::dropIfExists('devolucoes');
   }

};