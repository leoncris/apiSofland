<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuariosSofModel extends Model
{
    protected $connection = 'sqlsrv';
     protected $table = 'users'; 
     protected $primaryKey = 'id'; 
    // protected $keyType = 'string';
    // public $incrementing = false;
    //public $timestamps = false;

     protected $fillable = [
                   'id',
                    'name',
                    'username',
                    'email'
               ];


}
