<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
   
   protected $table = 'clientes';
   protected $primaryKey = 'clicodigo';
   public $timestamps = false;

   protected $fillable = [
      'clinome',
      'clicpf',
      'clidata_nascimento',
      'clitelefone',
      'cliendereco'
   ];

   public function vendas() {
      return $this->hasMany(Venda::class, 'clicodigo');
   }

}