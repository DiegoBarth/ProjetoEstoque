<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClienteAnexo extends Model {
   
   use HasFactory;

   protected $table = 'clientes_anexos';
   protected $primaryKey = 'anecodigo';
   public $timestamps = false;

   protected $casts = [
      'anearquivo' => 'string',
   ];

   protected $fillable = [
      'clicodigo',
      'anenome_arquivo',
      'anetipo',
      'anearquivo',
      'aneobservacao',
      'anedata_hora'
   ];

   public function cliente() {
      return $this->belongsTo(Cliente::class, 'clicodigo', 'clicodigo');
   }

}