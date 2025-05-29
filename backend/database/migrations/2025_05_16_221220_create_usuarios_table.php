<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('usuarios', function (Blueprint $table) {
         $table->id('usucodigo');
         $table->string('usunome', 100);
         $table->string('usunome_usuario', 20);
         $table->foreignId('usunivel')->constrained('niveis_usuarios', 'nucodigo');
         $table->boolean('usuativo')->default(true);
         $table->date('usudata_hora_criacao')->default(now());
         $table->text('ususenha');
      });
   }

   public function down(): void {
      Schema::dropIfExists('usuarios');
   }

};