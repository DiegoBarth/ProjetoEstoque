<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model {
   protected $table = 'metas';
   protected $primaryKey = 'mecodigo';

   protected $fillable = [
      'medescricao',
      'metipo',
      'mevalor_meta',
      'mequantidade_meta',
      'medata_inicio',
      'medata_fim'
   ];

   public function usuarios() {
      return $this->belongsToMany(Usuario::class, 'metas_usuarios', 'mecodigo', 'usucodigo');
   }

   public function produtos() {
      return $this->belongsToMany(Produto::class, 'metas_produtos', 'mecodigo', 'prododigo');
   }

}