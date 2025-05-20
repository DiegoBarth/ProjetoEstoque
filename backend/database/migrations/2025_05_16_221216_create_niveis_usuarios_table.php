<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('niveis_usuarios', function (Blueprint $table) {
         $table->id('nucodigo');
         $table->string('nunome', 20);
      });
   }

   public function down(): void {
      Schema::dropIfExists('niveis_usuarios');
   }
   
};