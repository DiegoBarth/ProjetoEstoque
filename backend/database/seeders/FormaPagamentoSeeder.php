<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormaPagamento;

class FormaPagamentoSeeder extends Seeder {

   public function run() {
      FormaPagamento::insert([
         ['fpnome' => 'Crédito'],
         ['fpnome' => 'Débito'],
         ['fpnome' => 'Dinheiro'],
         ['fpnome' => 'Pix']
      ]);
   }

}