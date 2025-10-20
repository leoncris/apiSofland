<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloModel extends Model
{
     protected $connection = 'sqlsrv_erp';
     protected $table = 'DEMO.ARTICULO'; 
     protected $primaryKey = 'ARTICULO'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;
    

    protected $fillable = [
        'ARTICULO',
        'DESCRIPCION',
        'ARTICULO_CUENTA',
        'COSTO_PROM_LOC',
        'TIPO',
     
    ];

       public function existencias()
    {
        return $this->hasMany(ExistenciaBodegaModel::class, 'ARTICULO', 'ARTICULO');
    }
    

}
