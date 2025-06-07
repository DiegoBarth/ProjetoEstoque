<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasTable extends Migration {
   public function up() {
      Schema::create('metas', function (Blueprint $table) {
         $table->id('mecodigo');
         $table->text('medescricao');
         $table->smallInteger('metipo')->comment('1 - Valor, 2 - Quantidade, 3 - Valor + Quantidade, 4 - Valor por Usuário, 5 - Quantidade por usuário, 6 - Valor + Quantidade por usuário, 7 - Valor por produto, 8 - Quantidade por produto, 9 - Valor + Quantidade por produto');
         $table->decimal('mevalor_meta', 15, 2)->nullable();
         $table->integer('mequantidade_meta')->nullable();
         $table->date('medata_inicio');
         $table->date('medata_fim');
      });
   }

   public function down() {
      Schema::dropIfExists('metas');
   }

}