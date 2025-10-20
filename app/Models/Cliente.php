<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
     protected $connection = 'sqlsrv_erp';
     protected $table = 'DEMO.CLIENTE'; 
     protected $primaryKey = 'CLIENTE'; 
     protected $keyType = 'string';
     public $incrementing = false;
     public $timestamps = false;


        // Lista de campos que son fechas
    protected $dateFields = ['FECHA_HORA_CREACION', 'FCH_HORA_ULT_MOD','FECHA_ULT_MOV','FECHA_INGRESO'];

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->dateFields) && !empty($value)) {
            $this->attributes[$key] = $this->normalizeDate($value);
        } else {
            parent::setAttribute($key, $value);
        }
    }

    private function normalizeDate($value)
    {
        try {
            // Formato dd/mm/YYYY
            return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            // Si ya viene en formato Y-m-d o es parseable
            return Carbon::parse($value)->format('Y-m-d');
        }
    }


     protected $fillable = [
                    'CLIENTE',
                    'NOMBRE',
                    'TELEFONO1',
                    'CONTRIBUYENTE',
                    'NIVEL_PRECIO',
                    'PAIS',
                    'ZONA',
                    'RUTA',
                    'VENDEDOR',
                    'COBRADOR',
                    'CATEGORIA_CLIENTE',
                    'E_MAIL',
                    'USUARIO_CREACION',
                    'FECHA_HORA_CREACION',
                    'USUARIO_ULT_MOD',
                    'FCH_HORA_ULT_MOD',
               ];

}
