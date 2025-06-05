<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model {
   
   protected $table = 'produtos';
   protected $primaryKey = 'procodigo';
   public $timestamps = false;

   protected $fillable = [
      'pronome',
      'procodigo_barras',
      'forcodigo',
      'procusto',
      'provalor',
      'provalor_desconto',
      'proestoque',
      'prodata_hora_cadastro',
      'proestoque_minimo_ideal'
   ];

   public function fornecedor() {
      return $this->belongsTo(Fornecedor::class, 'forcodigo');
   }

   public function itensVenda() {
      return $this->hasMany(ItemVenda::class, 'procodigo');
   }

}