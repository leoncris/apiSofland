<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloPrecioModel extends Model
{

     protected $connection = 'sqlsrv_erp';
     protected $table = 'demo.ARTICULO_PRECIO'; 
     protected $primarykey=['NIVEL_PRECIO','ARTICULO'];
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;

    protected $fillable = [
        'NIVEL_PRECIO',
        'PRECIO',
        'FECHA_INICIO',
        'FECHA_FIN',
    ];

    // Relación con articulo
    public function articulo()
    {
        return $this->belongsTo(ArticuloModel::class, 'ARTICULO', 'ARTICULO');
    }
}
