<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDevolucao extends Model {
   
   protected $table = 'itens_devolucoes';
   protected $primaryKey = 'idcodigo';
   public $timestamps = false;

   protected $fillable = [
      'decodigo',
      'ivcodigo',
      'idquantidade',
      'idsubtotal',
   ];

   public function devolucao() {
      return $this->belongsTo(Devolucao::class, 'decodigo');
   }

   public function itemVenda() {
      return $this->belongsTo(ItemVenda::class, 'ivcodigo');
   }

}