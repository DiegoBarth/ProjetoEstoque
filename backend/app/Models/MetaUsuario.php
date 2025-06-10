<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaUsuario extends Model {
   protected $table = 'metas_usuarios';
   protected $primaryKey = 'mucodigo';
   public $timestamps = false;

   protected $fillable = [
      'mecodigo',
      'usucodigo'
   ];

   public function meta() {
      return $this->belongsTo(Meta::class, 'mecodigo');
   }

   public function usuario() {
      return $this->belongsTo(Usuario::class, 'usucodigo');
   }

}