<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model {
   
   protected $table = 'fornecedores';
   protected $primaryKey = 'forcodigo';
   public $timestamps = false;

   protected $fillable = [
      'forrazao_social',
      'fornome_fantasia',
      'forcpfcnpj',
      'fortelefone',
      'foremail',
      'forendereco',
      'fordata_fundacao',
      'fordata_hora_criacao'
   ];

   public function produtos() {
      return $this->hasMany(Produto::class, 'forcodigo');
   }

}