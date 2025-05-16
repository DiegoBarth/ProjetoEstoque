<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   /**
    * Run the migrations.
    */
   public function up(): void {
      Schema::create('nivel_usuarios', function (Blueprint $table) {
         $table->id('nucodigo');
         $table->string('nunome', 20);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void {
      Schema::dropIfExists('nivel_usuarios');
   }
};