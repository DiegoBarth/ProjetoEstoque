<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('clientes_anexos', function (Blueprint $table) {
         $table->id('anecodigo');
         $table->foreignId('clicodigo')->constrained('clientes', 'clicodigo');
         $table->string('anenome_arquivo');
         $table->string('anetipo')->comment('1 - PDF, 2 - JPEG, 3 - PNG, 4 - BMP');
         $table->binary('anearquivo');
         $table->timestamp('anedata_hora');
      });
   }

   public function down(): void {
      Schema::dropIfExists('clientes_anexos');
   }

};