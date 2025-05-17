<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {

   protected $table = 'usuarios';
   protected $primaryKey = 'usucodigo';
   public $timestamps = false;

   protected $fillable = [
      'usunome',
      'usunome_usuario',
      'usunivel',
      'usuativo',
      'ususenha',
   ];

   public function nivel() {
      return $this->belongsTo(NivelUsuario::class, 'usunivel');
   }

   public function vendas() {
      return $this->hasMany(Venda::class, 'usucodigo');
   }

   public function devolucoes() {
      return $this->hasMany(Devolucao::class, 'usucodigo');
   }
   
}