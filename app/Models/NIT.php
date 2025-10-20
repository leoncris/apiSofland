<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NIT extends Model
{
    protected $connection = 'sqlsrv_erp';
     protected $primaryKey = 'NIT'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Cargar el esquema desde .env y aplicarlo al nombre de la tabla
        $this->table = env('DBSF_SCHEMA', 'dbo') . '.NIT';
    }

      protected $fillable = [
                    'NIT'.
                    'RAZON_SOCIAL',
                    'ALIAS',
                    'TIPO',
                    'ORIGEN',
                    'NUMERO_DOC_NIT',
                    'ACTIVO',
                    'TIPO_CONTRIBUYENTE',
                    'NRC',
                    'GIRO',
                    'CATEGORIA',
                    'TIPO_REGIMEN'
               ];

}
