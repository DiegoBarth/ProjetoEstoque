<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model {
   protected $table = 'metas';
   protected $primaryKey = 'mecodigo';

   public $timestamps = false;

   protected $fillable = [
      'medescricao',
      'metipo',
      'mevalor_meta',
      'mequantidade_meta',
      'medata_inicio',
      'medata_fim'
   ];

   public function usuarios() {
      return $this->hasMany(MetaUsuario::class, 'mecodigo');
   }
   
   public function produtos() {
      return $this->hasMany(MetaProduto::class, 'mecodigo');
   }

}