<?php

namespace App\Http\Controllers;

use App\Models\BodegaModel;
use App\Models\EmpresaModel;
use App\Models\master_integraModel;
use App\Models\NivelPrecioModel;
use App\Models\User;
use App\Models\VendedorModel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class MastersContoller extends Controller
{
    public function getbodegas(  )
    {
            $bodega = BodegaModel ::select('BODEGA','NOMBRE')
                            // ->where('NIVEL_PRECIO','=', $nivelprecio->nivel_precio)
                             ->get();

            if (!$bodega  ){
                $data =[
                    'mesage'=>'No hay bodegas',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

            $data = [
                    'bodegas' => $bodega,
                    'status' => 200
                    ];

            return response()->json($data ,200);
    }

    public function getvendedores(  )
    {
            // $vendedores = VendedorModel::select('VENDEDOR','NOMBRE','ACTIVO' ,'U_ESSUCURSAL')
            //                 ->where('U_ESSUCURSAL','=', 'SI')
            //                  ->get();

            $vendedores = VendedorModel::select('VENDEDOR', 'NOMBRE', 'ACTIVO', 'U_ESSUCURSAL')
                           ->where('ACTIVO', 'S')
                            ->where('U_ESSUCURSAL', 'SI')
                            ->whereNotIn('VENDEDOR', function ($query) {
                                $query->select('id_vendedor')
                                    ->from(DB::raw('DmSoftVtas.dbo.masters'));
                            })
                            ->get();

            if (!$vendedores  ){
                $data =[
                    'mesage'=>'No hay Vendedores',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

            $data = [
                    'vendedores' => $vendedores,
                    'status' => 200
                    ];

            return response()->json($data ,200);
    }

    public function getempresas(  )
    {
            $empresa = EmpresaModel::select('CONJUNTO','NOMBRE','PAIS')
                            //->where('U_ESSUCURSAL','=', 'SI')
                             ->get();

            if (!$empresa  ){
                $data =[
                    'mesage'=>'No hay Vendedores',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

            $data = [
                    'empresas' => $empresa,
                    'status' => 200
                    ];

            return response()->json($data ,200);
    }

    public function getUsuarios(  )
    {
            $usuarios = User::select('name','username','email')
                            ->whereNotIn('id', [1, 2, 3]) 
                            ->whereNotIn('name', function ($query) {
                                $query->select('id_usuario')->from('masters');
                            })
                            ->get();


            if (!$usuarios  ){
                $data =[
                    'mesage'=>'No hay usuarios',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

            $data = [
                    'usuarios' => $usuarios,
                    'status' => 200
                    ];

            return response()->json($data ,200);
    }
    public function getNivelprecios(  )
    {
            $nivelpre = NivelPrecioModel ::select('NIVEL_PRECIO','MONEDA')
                            // ->where('NIVEL_PRECIO','=', $nivelprecio->nivel_precio)
                             ->get();

            if (!$nivelpre ){
                $data =[
                    'mesage'=>'No hay Niveles de precios',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

            $data = [
                    'nivelprecio' => $nivelpre,
                    'status' => 200
                    ];

            return response()->json($data ,200);
    }

}
