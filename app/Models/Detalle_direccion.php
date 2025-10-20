<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_direccion extends Model
{
     protected $connection = 'sqlsrv_erp';
     protected $table = 'DEMO.DETALLE_DIRECCION'; 
     protected $primaryKey = 'DETALLE_DIRECCION'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;


      protected $fillable = [
                    'DETALLE_DIRECCION',	
                    'DIRECCION',	
                    'CAMPO_1',
               ];

}
