<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendedorModel extends Model
{
    
     protected $connection = 'sqlsrv_erp';
     protected $table = 'DEMO.VENDEDOR'; 
     protected $primarykey='VENDEDOR';
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;

    protected $fillable = [
     'VENDEDOR',
    'NOMBRE',
    'ACTIVO',
    'U_ESSUCURSAL'
    ];
}
