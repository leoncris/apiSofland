<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class master_integraModel extends Model
{
       protected $table='masters';
       protected $primarykey='id';
    
        protected $fillable =[
                'id',	
                'conjunto',
                'id_usuario',
                'id_vendedor',
                'id_bodega',
                'nivel_precio',
                'descripcion',
                'tipo',
                'activo'
        ];
}
