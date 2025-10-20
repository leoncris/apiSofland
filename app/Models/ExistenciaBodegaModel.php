<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExistenciaBodegaModel extends Model
{
     
     protected $connection = 'sqlsrv_erp';
     protected $table = 'demo.existencia_bodega';
     protected $primarykey=['ARTICULO','BODEGA'];
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;

    protected $fillable = [
        'ARTICULO',
        'BODEGA',
        'CANT_DISPONIBLE',
    ];

    // Relación con articulo
    public function articulo()
    {
        return $this->belongsTo(ArticuloModel::class, 'ARTICULO', 'ARTICULO');
    }

     public function series()
    {
        return $this->hasMany(ExistenciaSerieModel::class, 'ARTICULO', 'ARTICULO')
                    ->whereColumn('BODEGA', 'BODEGA');
    }
    public function precio()
    {
        return $this->hasOne(ArticuloPrecioModel::class, 'ARTICULO', 'ARTICULO');
    }

}
