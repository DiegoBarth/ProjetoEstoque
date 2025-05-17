<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\NivelUsuarioSeeder;
use Database\Seeders\FormaPagamentoSeeder;

class DatabaseSeeder extends Seeder {

   public function run(): void {
      $this->call([
         NivelUsuarioSeeder::class,
         FormaPagamentoSeeder::class
      ]);
   }

}