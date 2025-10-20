<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaModel extends Model
{
     protected $connection = 'sqlsrv_erp';
     protected $table = 'DEMO.BODEGA'; 
     protected $primaryKey = 'BODEGA'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;

     protected $fillable = [
                    'BODEGA',
                    'NOMBRE'
               ];
}
