<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelPrecioModel extends Model
{
    
     //protected $connection = 'sqlsrv_Softland';
     protected $connection = 'sqlsrv_erp';
     protected $table = 'demo.NIVEL_PRECIO'; 
     protected $primaryKey = 'NIVEL_PRECIO'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;

     protected $fillable = [
                    'NIVEL_PRECIO',
                    'MONEDA'
               ];
}
