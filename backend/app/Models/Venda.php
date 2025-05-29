<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model {
   protected $table = 'vendas';
   protected $primaryKey = 'vecodigo';
   public $timestamps = false;

   protected $fillable = [
      'clicodigo',
      'usucodigo',
      'fpcodigo',
      'venumero_parcelas',
      'vedesconto',
      'vevalor_total',
      'vesituacao',
      'vedata_venda'
   ];

   public function cliente() {
      return $this->belongsTo(Cliente::class, 'clicodigo');
   }

   public function usuario() {
      return $this->belongsTo(Usuario::class, 'usucodigo');
   }

   public function formaPagamento() {
      return $this->belongsTo(FormaPagamento::class, 'fpcodigo');
   }

   public function itens() {
      return $this->hasMany(ItemVenda::class, 'vecodigo');
   }

   public function devolucoes() {
      return $this->hasMany(Devolucao::class, 'vecodigo');
   }
   
}