<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExistenciaSerieModel extends Model
{
    
     protected $connection = 'sqlsrv_erp';
     protected $table = 'demo.EXISTENCIA_SERIE';
     protected $primarykey=['ARTICULO','BODEGA'];
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;

    protected $fillable = [
     'BODEGA', 
     'LOTE', 
     'TIPO', 
     'LOCALIZACION', 
     'ARTICULO', 
     'SERIE_INICIAL', 
     'SERIE_FINAL', 
     'CANTIDAD'
    ];

    // Relación con articulo
    public function articulo()
    {
        return $this->belongsTo(ArticuloModel::class, 'ARTICULO', 'ARTICULO');
    }

}
