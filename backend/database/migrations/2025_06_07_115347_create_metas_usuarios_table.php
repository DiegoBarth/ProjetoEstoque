<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasUsuariosTable extends Migration {
   public function up() {
      Schema::create('metas_usuarios', function (Blueprint $table) {
         $table->id('mucodigo');
         $table->foreignId('mecodigo')->constrained('metas', 'mecodigo');
         $table->foreignId('usucodigo')->constrained('usuarios', 'usucodigo');
      });
   }

   public function down() {
      Schema::dropIfExists('metas_usuarios');
   }

}