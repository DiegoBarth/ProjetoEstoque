<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable {
   
   use HasApiTokens, Notifiable;

   protected $table = 'usuarios';
   protected $primaryKey = 'usucodigo';
   public $timestamps = false;

   protected $fillable = [
      'usunome',
      'usunome_usuario',
      'usunivel',
      'usuativo',
      'ususenha',
      'usudata_hora_criacao'
   ];

   protected $hidden = [
      'ususenha',
      'remember_token',
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

   public function getAuthPassword() {
      return $this->ususenha;
   }

   public function username() {
      return 'usunome_usuario';
   }

}