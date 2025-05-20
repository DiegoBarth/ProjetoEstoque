<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('produtos', function (Blueprint $table) {
         $table->id('procodigo');
         $table->string('pronome', 50);
         $table->string('procodigo_barras', 20)->nullable();
         $table->foreignId('forcodigo')->constrained('fornecedores', 'forcodigo');
         $table->decimal('procusto', 10, 2);
         $table->decimal('provalor', 10, 2);
         $table->decimal('provalor_desconto', 10, 2);
         $table->integer('proestoque');
      });
   }

   public function down(): void {
      Schema::dropIfExists('produtos');
   }
   
};