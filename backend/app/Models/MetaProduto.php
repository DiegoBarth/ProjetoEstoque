<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaProduto extends Model {
   protected $table = 'metas_produtos';
   protected $primaryKey = 'mpcodigo';

   protected $fillable = [
      'mecodigo',
      'prododigo'
   ];

   public function meta() {
      return $this->belongsTo(Meta::class, 'mecodigo');
   }

   public function produto() {
      return $this->belongsTo(Produto::class, 'prododigo');
   }

}