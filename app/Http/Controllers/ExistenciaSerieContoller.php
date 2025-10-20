<?php

namespace App\Http\Controllers;

use App\Models\ExistenciaSerieModel;
use App\Models\master_integraModel;
use Illuminate\Http\Request;

class ExistenciaSerieContoller extends Controller
{
    public function getexistenciaserie( $vend,$art, $serie )
    {
        //$precio=master_integraModel::on('sqlser_filament');
        $tmaster =  master_integraModel::where('id_vendedor', $vend)->firstOrFail();


            if ( $tmaster->activo == '0') {
                return response()->json([
                    'message' => 'Usuario inactivo',
                    'status'  => 404
                ], 404);
            }

           $existenciaserie = ExistenciaSerieModel::select(
                        'BODEGA',
                        'ARTICULO', 'SERIE_INICIAL', 'SERIE_FINAL', 'CANTIDAD'
                    )
                    ->where('BODEGA', $tmaster->id_bodega) 
                    ->where('ARTICULO', $art) 
                    ->where('SERIE_INICIAL', $serie )
                    //->where('SERIE_FINAL', '350057711559861')
                    ->get();
        

            if (!$existenciaserie ){
                $data =[
                    'mesage'=>'No hay existencias',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

          $data = [
            'existencia_serie' => $existenciaserie ,
            'status' => 200
              ];

            return response()->json($data ,200);

    }

    public function getexistenciaserietotal( $vend,$art )
    {
        //$precio=master_integraModel::on('sqlser_filament');
        $tmaster =  master_integraModel::where('id_vendedor', $vend)->firstOrFail();


            if ( $tmaster->activo == '0') {
                return response()->json([
                    'message' => 'Usuario inactivo',
                    'status'  => 404
                ], 404);
            }

           $existenciaserie = ExistenciaSerieModel::select(
                        'BODEGA', 'LOTE', 'TIPO', 'LOCALIZACION', 
                        'ARTICULO', 'SERIE_INICIAL', 'SERIE_FINAL', 'CANTIDAD'
                    )
                    ->where('BODEGA', $tmaster->id_bodega) 
                    ->where('ARTICULO', $art) 
                    //->where('SERIE_INICIAL', '350057711559861')
                    //->where('SERIE_FINAL', '350057711559861')
                    ->get();
        

            if (!$existenciaserie ){
                $data =[
                    'mesage'=>'No hay existencias',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }
          $data = [
            'existencias' => $existenciaserie,
            'status' => 200
        ];
          

            return response()->json($data ,200);

    }
}
