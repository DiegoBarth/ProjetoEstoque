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
      'cliendereco',
      'clidata_hora_criacao'
   ];

   public function vendas() {
      return $this->hasMany(Venda::class, 'clicodigo');
   }

   public function anexos() {
      return $this->hasMany(ClienteAnexo::class, 'clicodigo', 'clicodigo');
   }

}