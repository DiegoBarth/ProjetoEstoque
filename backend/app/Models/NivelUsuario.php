<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelUsuario extends Model {

   protected $table = 'niveis_usuarios';
   protected $primaryKey = 'nucodigo';
   public $timestamps = false;

   protected $fillable = ['nunome'];
   
}