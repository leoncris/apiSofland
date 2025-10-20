<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente_Vendedor extends Model
{
     protected $connection = 'sqlsrv_erp';
     protected $primaryKey = 'CLIENTE'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Cargar el esquema desde .env y aplicarlo al nombre de la tabla
        $this->table = env('DBSF_SCHEMA', 'dbo') . '.CLIENTE_VENDEDOR';
    }

      protected $fillable = [
                   'CLIENTE',
                    'VENDEDOR'
               ];
}
