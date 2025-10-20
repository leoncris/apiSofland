<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion_Embarque extends Model
{
    protected $connection = 'sqlsrv_erp';
     ///protected $table = 'demo.DIRECC_EMBARQUE'; 
     protected $primaryKey = 'DETALLE_DIRECCION'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Cargar el esquema desde .env y aplicarlo al nombre de la tabla
        $this->table = env('DBSF_SCHEMA', 'dbo') . '.DIRECC_EMBARQUE';
    }

      protected $fillable = [
                   'CLIENTE',
                    'DIRECCION',
                    'DETALLE_DIRECCION',
                    'DESCRIPCION',
                    'CONTACTO',
                    'CARGO',
                    'TELEFONO1',
                    'TELEFONO2',
                    'FAX',
                    'EMAIL'
               ];

}
