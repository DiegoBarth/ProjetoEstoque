<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model {

   protected $table = 'formas_pagamento';
   protected $primaryKey = 'fpcodigo';
   public $timestamps = false;

   protected $fillable = ['fpnome'];
   
}