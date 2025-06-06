<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('clientes', function (Blueprint $table) {
         $table->id('clicodigo');
         $table->string('clinome', 100);
         $table->string('clicpf', 11);
         $table->date('clidata_nascimento');
         $table->timestamp('clidata_hora_criacao');
         $table->string('clitelefone', 11)->nullable();
         $table->text('cliendereco')->nullable();
      });
   }

   public function down(): void {
      Schema::dropIfExists('clientes');
   }
   
};