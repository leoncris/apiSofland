<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaModel extends Model
{
     protected $connection = 'sqlsrv_erp';
     protected $table = 'ERPADMIN.CONJUNTO'; 
     protected $primaryKey = 'CONJUNTO'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;

     protected $fillable = [
                    'CONJUNTO',
                    'NOMBRE',
                    'PAIS'
               ];
}
