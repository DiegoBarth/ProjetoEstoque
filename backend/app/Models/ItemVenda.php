<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model {
   
   protected $table = 'itens_vendas';
   protected $primaryKey = 'ivcodigo';
   public $timestamps = false;

   protected $fillable = [
      'vecodigo',
      'procodigo',
      'ivquantidade',
      'ivpreco_unitario',
      'ivsubtotal',
   ];

   public function venda() {
      return $this->belongsTo(Venda::class, 'vecodigo');
   }

   public function produto() {
      return $this->belongsTo(Produto::class, 'procodigo');
   }

   public function itensDevolucao() {
      return $this->hasMany(ItemDevolucao::class, 'ivcodigo');
   }

}