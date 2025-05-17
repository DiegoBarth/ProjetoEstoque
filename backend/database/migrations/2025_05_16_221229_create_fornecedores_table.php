<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

   public function up(): void {
      Schema::create('fornecedores', function (Blueprint $table) {
         $table->id('forcodigo');
         $table->string('forrazao_social', 150);
         $table->string('fornome_fantasia', 150)->nullable();
         $table->string('forcpfcnpj', 14)->unique();
         $table->string('fortelefone', 11)->nullable();
         $table->string('foremail', 30)->nullable();
         $table->text('forendereco')->nullable();
         $table->date('fordata_fundacao')->nullable();
      });
   }

   public function down(): void {
      Schema::dropIfExists('fornecedores');
   }
   
};