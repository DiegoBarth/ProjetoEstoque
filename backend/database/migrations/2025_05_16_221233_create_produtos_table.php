<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('produtos', function (Blueprint $table) {
         $table->id('procodigo');
         $table->string('pronome', 50);
         $table->foreignId('forcodigo')->constrained('fornecedores', 'forcodigo');
         $table->decimal('forcusto', 10, 2);
         $table->decimal('forvalor', 10, 2);
         $table->integer('forestoque');
      });
   }

   public function down(): void {
      Schema::dropIfExists('produtos');
   }
   
};