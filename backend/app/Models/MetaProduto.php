<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaProduto extends Model {
   protected $table = 'metas_produtos';
   protected $primaryKey = 'mpcodigo';
   public $timestamps = false;

   protected $fillable = [
      'mecodigo',
      'procodigo'
   ];

   public function meta() {
      return $this->belongsTo(Meta::class, 'mecodigo');
   }

   public function produto() {
      return $this->belongsTo(Produto::class, 'procodigo');
   }

}