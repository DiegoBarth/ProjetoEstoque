<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasProdutosTable extends Migration {
   public function up() {
      Schema::create('metas_produtos', function (Blueprint $table) {
         $table->id('mpcodigo');
         $table->foreignId('mecodigo')->constrained('metas', 'mecodigo');
         $table->foreignId('procodigo')->constrained('produtos', 'procodigo');
      });
   }

   public function down() {
      Schema::dropIfExists('metas_produtos');
   }

}