<?php

namespace App\Http\Controllers;

use App\Models\ArticuloPrecioModel;
use App\Models\ExistenciaBodegaModel;
use App\Models\master_integraModel;
use Illuminate\Http\Request;

class ExistenciaBodegaContoller extends Controller
{
    public function getexistencias( $vend,$art )
    {
        //$precio=master_integraModel::on('sqlser_filament');
        $tmaster =  master_integraModel::where('id_vendedor', $vend)->firstOrFail();

        $nivelprecio = master_integraModel::select('nivel_precio','activo')
            ->where('id_bodega', $tmaster->id_bodega )
            ->WHERE('id_vendedor','=', $vend)
            ->first();

           if (!$nivelprecio) {
                return response()->json([
                    'message' => 'No se encontró nivel de precio para este vendedor/bodega',
                    'status'  => 404
                ], 404);
            }

            if ( $tmaster->activo == '0') {
                return response()->json([
                    'message' => 'Usuario inactivo',
                    'status'  => 404
                ], 404);
            }
            $precio = ArticuloPrecioModel::select('NIVEL_PRECIO', 'PRECIO', 'FECHA_INICIO', 'FECHA_FIN')
                             ->where('NIVEL_PRECIO','=', $nivelprecio->nivel_precio)
                             ->where('ARTICULO', $art)
                             ->first();


              if (!$precio){
                $data =[
                    
                    'mesage'=> 'No hay precio definido',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }


            $existencia = ExistenciaBodegaModel::with([
                    'precio' => function($query) use ($nivelprecio, $art) {
                        $query->select('ARTICULO','NIVEL_PRECIO','PRECIO','FECHA_INICIO','FECHA_FIN')
                            ->where('NIVEL_PRECIO', $nivelprecio->nivel_precio)
                            ->where('ARTICULO', $art);
                    }
                    // ,
                    // 'series' => function($query) {
                    //     $query->select('ARTICULO','BODEGA','SERIE_INICIAL','SERIE_FINAL','CANTIDAD');
                    // }
                ])
                ->where('BODEGA', $tmaster->id_bodega)
                ->where('ARTICULO', $art)
                ->first();
        

            if (!$existencia ){
                $data =[
                    'mesage'=>'No hay existencias',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

            //  $data =[
            //        // 'existencia' =>$existencia,
            //        'nivel_precio'=> $precio,
            //         'status'=>200             
            // ];
        $data = [
                'existencia' => [
                        'articulo' => $existencia->ARTICULO,
                        'bodega'   => $existencia->BODEGA,
                        'precio' => $existencia->precio,
                        'cantidad' => $existencia->CANT_DISPONIBLE
                        //'series'   => $existencia->series //->pluck('SERIE_INICIAL') 
                    ],
                    'status' => 200
                 ];

            return response()->json($data ,200);

    }
}
