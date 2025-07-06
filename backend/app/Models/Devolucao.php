<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucao extends Model {
   
   protected $table = 'devolucoes';
   protected $primaryKey = 'decodigo';
   public $timestamps = false;

   protected $fillable = [
      'vecodigo',
      'usucodigo',
      'demotivo',
      'devalor_total',
      'dedata_hora_devolucao'
   ];

   public function venda() {
      return $this->belongsTo(Venda::class, 'vecodigo');
   }

   public function usuario() {
      return $this->belongsTo(Usuario::class, 'usucodigo');
   }

   public function itens() {
      return $this->hasMany(ItemDevolucao::class, 'decodigo');
   }

}