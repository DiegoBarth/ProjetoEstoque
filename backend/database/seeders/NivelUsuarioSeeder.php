<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NivelUsuario;

class NivelUsuarioSeeder extends Seeder {
   public function run() {
      NivelUsuario::insert([
         ['nunome' => 'Administrador'],
         ['nunome' => 'Caixa']
      ]);
   }

}