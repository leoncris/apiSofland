<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cliente_Vendedor;
use App\Models\Detalle_direccion;
use App\Models\Direccion_Embarque;
use App\Models\master_integraModel;
use App\Models\NIT;
use Illuminate\Http\Request;
Use Illuminate\support\Facades\Validator;
use Carbon\Carbon;

use Illuminate\support\Facades\DB; 

class ClienteController extends Controller
{
         public function getClientes( $vend )
    {
        //$precio=master_integraModel::on('sqlser_filament');
        $tmaster =  master_integraModel::where('id_vendedor', $vend)->firstOrFail();


            if ( $tmaster->activo == '0') {
                return response()->json([
                    'message' => 'Usuario inactivo',
                    'status'  => 404
                ], 404);
            }

           $clientes = Cliente::SELECT ('CLIENTE','NOMBRE','TELEFONO1','CONTRIBUYENTE','NIVEL_PRECIO','PAIS','ZONA',
                                        'RUTA','VENDEDOR','COBRADOR','CATEGORIA_CLIENTE','E_MAIL','DIVISION_GEOGRAFICA1','DIVISION_GEOGRAFICA2')
                    ->paginate(50);
        
            if (!$clientes){
                $data =[
                    'mesage'=>'No Existe el cliente',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

          $data = [
            'cliente' => $clientes,
            'status' => 200
              ];

            return response()->json($data ,200);

    }    
     public function getCliente( $vend,$cliente )
    {
        //$precio=master_integraModel::on('sqlser_filament');
        $tmaster =  master_integraModel::where('id_vendedor', $vend)->firstOrFail();


            if ( $tmaster->activo == '0') {
                return response()->json([
                    'message' => 'Usuario inactivo',
                    'status'  => 404
                ], 404);
            }

           $cliente = Cliente::SELECT ('CLIENTE','NOMBRE','TELEFONO1','CONTRIBUYENTE','NIVEL_PRECIO','PAIS','ZONA',
                                        'RUTA','VENDEDOR','COBRADOR','CATEGORIA_CLIENTE','E_MAIL','DIVISION_GEOGRAFICA1','DIVISION_GEOGRAFICA2')
                    ->where('CLIENTE', $cliente) 
                    ->get();
        

            if (!$cliente){
                $data =[
                    'mesage'=>'No Existe el cliente',
                    'status'=>404
                ];
                
                return response()->json($data,404);

            }

          $data = [
            'cliente' => $cliente,
            'status' => 200
              ];

            return response()->json($data ,200);

    }

    public function psStore( Request $request)
    {

        $vend =$request->vendedor;
        //$precio=master_integraModel::on('sqlser_filament');
        $tmaster =  master_integraModel::where('id_vendedor', $vend)->firstOrFail();

       
            if ( $tmaster->activo == '0') {
                return response()->json([
                    'message' => 'Usuario inactivo',
                    'status'  => 404
                ], 404);
            }

            //revisa si el cliente existe
        $clienteexi = Cliente::where('CLIENTE',$request->DUI)->first();
          if ($clienteexi ) {
                return response()->json([
                    'message' => 'El cliente ya existe',
                    'estatus' => 409
                ], 409);
            }

            ///////Validando todo el Request
            $validator = Validator::make($request->all(),[
                    'DUI'=>'required',
                    'NOMBRE'=>'required|max:255',
                    'TELEFONO'=>'required',
                    'CORREO'=>'required',
                    'DIRECCION'=>'required',
                    'NRC'=> 'nullable',
                    'GIRO'=> 'nullable',
            ]);
            
            if ($validator->fails()){
                $data =[
                        'message'=> 'Error en la validacion de los datos de Clientes',
                        'errors'=>$validator->errors(),
                        'estatus'=>400
                ];
                return response()->json($data,400);
            }

            $diahora = Carbon::now();

            
                
            // 🔒 INICIO DE TRANSACCIÓN 
            DB::beginTransaction();

            try {

                        //////**************pasos para insertar correctamente el cliente. 1 detalle de direccion
                        ///select * from demo.DETALLE_DIRECCION
                    $detdireccion=  DB::connection('sqlsrv_erp')
                                    ->table('demo.DETALLE_DIRECCION')
                                    ->select('DETALLE_DIRECCION')->orderBy('DETALLE_DIRECCION','desc')
                                    ->first();
                    ///revisa el ultimo y le suma una
                        $newconsecdetalle = $detdireccion-> DETALLE_DIRECCION + 1;
                        $detalledireccion = new Detalle_direccion();

                        $detalledireccion->DETALLE_DIRECCION = $newconsecdetalle;
                        $detalledireccion->DIRECCION ='ESTANDAR';
                        $detalledireccion->CAMPO_1= $request->DIRECCION;

                        $detalledireccion->save();
                        //////**************pasos para insertar correctamente el cliente. 2 nit
                        //****************** revisa si el NIT existe
                       $nit = NIT::where('NIT',$request->DUI)->first();


                        if (!$nit) {
                            $nit =new NIT();

                            $nit->NIT = $request->DUI;	
                            $nit->RAZON_SOCIAL=$request->NOMBRE;	///DEMO
                            $nit->ALIAS=$request->NOMBRE;	///DEMO
                            $nit->NOTAS='NIT creado por Api';
                            $nit->TIPO= //NIT
                            $nit->DIGITO_VERIFICADOR=NULL;
                            $nit->USA_REPORTE_D151='N';
                            $nit->ORIGEN='O';
                            $nit->NUMERO_DOC_NIT = $request->DUI;	//9642-220681-001-2
                            $nit->SUCURSAL=NULL;
                            $nit->PRIMER_NOMBRE=NULL;
                            $nit->SEGUNDO_NOMBRE=NULL;
                            $nit->PRIMER_APELLIDO=NULL;
                            $nit->SEGUNDO_APELLIDO=NULL;
                            $nit->TIPO_DOCUMENTO=NULL;
                            $nit->CLASE_DOCUMENTO=NULL;
                            $nit->DEPARTAMENTO=NULL;
                            $nit->MUNICIPIO=NULL;
                            $nit->PAIS=NULL;
                            $nit->EXTERIOR=0;
                            $nit->DETALLE_DIRECCION=NULL;
                            $nit->DIRECCION='';
                            $nit->NATURALEZA='J';
                            $nit->ACTIVIDAD_ECONOMINA=NULL;
                            $nit->CORREO=NULL;
                            $nit->TELEFONO=NULL;
                            $nit->CELULAR=NULL;
                            $nit->ACTIVO='S';
                            $nit->TIPO_CONTRIBUYENTE='F';
                            $nit->NRC =$request->NRC;	//602-5
                            $nit->GIRO =$request->GIRO;	//Venta de combustibles y lubricantes
                            $nit->CATEGORIA='OTROS';	//GRANDE
                            $nit->DUI=NULL;
                            $nit->TIPO_REGIMEN='C';
                            $nit->PASAPORTE=NULL;
                            $nit->CARNE=NULL;
                            $nit->OTRO=NULL;
                            $nit->INF_LEGAL='N';
                            $nit->cod_postal=NULL;
                            $nit->oblig_respon_rut=NULL;
                            $nit->responsable_rut=NULL;
                            $nit->TRIBUTO_RUT=NULL;
                            $nit->COD_INTERNO_EMP=NULL;
                            $nit->CARGO=NULL;
                            $nit->AREA_DEPTO_SECC=NULL;
                            $nit->GLN=NULL;
                            $nit->XSLT_PERSONALIZADO=NULL;
                            $nit->XSLT_PERSONALIZADO_CREDITO=NULL;
                            $nit->UBICACION=NULL;
                            $nit->EMAIL_DOC_ELECTRONICO=NULL;
                            $nit->EMAIL_DOC_ELECTRONICO_COPIA=NULL;
                            $nit->DETALLAR_KITS='N';
                            $nit->ACEPTA_DOC_ELECTRONICO='N';
                            $nit->CODIGO_EMPLEADO=NULL;
                            $nit->DIRECCION_ADICIONAL=NULL;
                            $nit->ACTIVIDAD_COMERCIAL=NULL;
                            $nit->TIPO_PERSONA=NULL;
                            $nit->U_SECTOR='0';

                            $nit->save();
                        }



                        ////////////////Guardamos el  cliente PASO 3
                        $cliente = new Cliente();  
                        $cliente->CLIENTE = $request->DUI;
                        $cliente->NOMBRE= $request->NOMBRE;	//NICOLAS ALFREDO TOBAR AYALA.
                        $cliente->DETALLE_DIRECCION= $newconsecdetalle; //'0';	 //6337
                        $cliente->ALIAS	= $request->NOMBRE;//NICOLAS ALFREDO TOBAR AYALA
                        $cliente->CONTACTO='ND';
                        $cliente->CARGO	='ND';
                        $cliente->DIRECCION = $request->DIRECCION; //	:: PLANTA SOYAPANGO EMPLEADO
                        $cliente->DIR_EMB_DEFAULT='ND';
                        $cliente->TELEFONO1 = $request->TELEFONO;	 //
                        $cliente->TELEFONO2	='ND';// 
                        $cliente->FAX='ND';
                        $cliente->CONTRIBUYENTE = $request->DUI; //	0619-061267-106-6
                        $cliente->FECHA_INGRESO =$diahora;	//2019-03-02 00:00:00.000
                        $cliente->MULTIMONEDA='N';
                        $cliente->MONEDA='USD';
                        $cliente->SALDO='0';
                        $cliente->SALDO_LOCAL='0';
                        $cliente->SALDO_DOLAR='0';
                        $cliente->SALDO_CREDITO='0';
                        $cliente->SALDO_NOCARGOS='0';
                        $cliente->LIMITE_CREDITO='0';
                        $cliente->EXCEDER_LIMITE='N';
                        $cliente->TASA_INTERES='0';
                        $cliente->TASA_INTERES_MORA='0';
                        $cliente->FECHA_ULT_MORA='1980-01-01 00:00:00.000';
                        $cliente->FECHA_ULT_MOV=$diahora;
                        $cliente->CONDICION_PAGO='0';
                        $cliente->NIVEL_PRECIO='ND-LOCAL';
                        $cliente->DESCUENTO	='0';
                        $cliente->MONEDA_NIVEL='L';
                        $cliente->ACEPTA_BACKORDER='N';
                        $cliente->PAIS='SAL';
                        $cliente->ZONA='ND';
                        $cliente->RUTA='ND';
                        $cliente->VENDEDOR=$vend;
                        $cliente->COBRADOR='ND';
                        $cliente->ACEPTA_FRACCIONES='N';
                        $cliente->ACTIVO='S';
                        $cliente->EXENTO_IMPUESTOS='N';
                        $cliente->EXENCION_IMP1='0';
                        $cliente->EXENCION_IMP2='0';
                        $cliente->COBRO_JUDICIAL='N';
                        $cliente->CATEGORIA_CLIENTE='C-TIENDA';
                        $cliente->CLASE_ABC='S';
                        $cliente->DIAS_ABASTECIMIEN='0';
                        $cliente->USA_TARJETA='N';
                        $cliente->TARJETA_CREDITO=NULL;
                        $cliente->TIPO_TARJETA=NULL;
                        $cliente->FECHA_VENCE_TARJ=NULL;
                        $cliente->E_MAIL=$request->CORREO;
                        $cliente->REQUIERE_OC='N';
                        $cliente->ES_CORPORACION='S';
                        $cliente->CLI_CORPORAC_ASOC=NULL;
                        $cliente->REGISTRARDOCSACORP='N';
                        $cliente->USAR_DIREMB_CORP='N';
                        $cliente->APLICAC_ABIERTAS='N';
                        $cliente->VERIF_LIMCRED_CORP='N';	
                        $cliente->USAR_DESC_CORP='N';
                        $cliente->DOC_A_GENERAR='F';
                        $cliente->RUBRO1_CLIENTE=NULL;
                        $cliente->RUBRO2_CLIENTE=NULL;
                        $cliente->RUBRO3_CLIENTE=NULL;
                        $cliente->TIENE_CONVENIO='N';
                        $cliente->NOTAS	='creado desde Api Movil';
                        $cliente->DIAS_PROMED_ATRASO='0';
                        $cliente->RUBRO1_CLI=NULL;
                        $cliente->RUBRO2_CLI=NULL;
                        $cliente->RUBRO3_CLI=NULL;
                        $cliente->RUBRO4_CLI=NULL;
                        $cliente->RUBRO5_CLI=NULL;
                        $cliente->ASOCOBLIGCONTFACT='N';
                        $cliente->RUBRO4_CLIENTE=NULL;
                        $cliente->RUBRO5_CLIENTE=NULL;
                        $cliente->RUBRO6_CLIENTE=NULL;
                        $cliente->RUBRO7_CLIENTE=NULL;
                        $cliente->RUBRO8_CLIENTE=NULL;
                        $cliente->RUBRO9_CLIENTE=NULL;
                        $cliente->RUBRO10_CLIENTE=NULL;
                        $cliente->USAR_PRECIOS_CORP='N';
                        $cliente->USAR_EXENCIMP_CORP='N';
                        $cliente->DIAS_DE_COBRO=NULL;
                        $cliente->AJUSTE_FECHA_COBRO='A';
                        $cliente->GLN=NULL;
                        $cliente->UBICACION=NULL;
                        $cliente->CLASE_DOCUMENTO='N';
                        $cliente->LOCAL='L';
                        $cliente->TIPO_CONTRIBUYENTE='F';
                        $cliente->RUBRO11_CLIENTE=NULL;
                        $cliente->RUBRO12_CLIENTE=NULL;
                        $cliente->RUBRO13_CLIENTE=NULL;
                        $cliente->RUBRO14_CLIENTE=NULL;
                        $cliente->RUBRO15_CLIENTE=NULL;
                        $cliente->RUBRO16_CLIENTE=NULL;
                        $cliente->RUBRO17_CLIENTE=NULL;
                        $cliente->RUBRO18_CLIENTE=NULL;
                        $cliente->RUBRO19_CLIENTE=NULL;
                        $cliente->RUBRO20_CLIENTE=NULL;
                        $cliente->MODELO_RETENCION=NULL;
                        $cliente->ACEPTA_DOC_ELECTRONICO='N';
                        $cliente->CONFIRMA_DOC_ELECTRONICO='N';
                        $cliente->EMAIL_DOC_ELECTRONICO=$request->CORREO;
                        $cliente->EMAIL_PED_EDI=NULL;
                        $cliente->ACEPTA_DOC_EDI='N';
                        $cliente->NOTIFICAR_ERROR_EDI='N';
                        $cliente->EMAIL_ERROR_PED_EDI=NULL;
                        $cliente->CODIGO_IMPUESTO='IVA';
                        $cliente->DIVISION_GEOGRAFICA1=NULL;
                        $cliente->DIVISION_GEOGRAFICA2=NULL;
                        $cliente->REGIMEN_TRIB='NU';
                        $cliente->MOROSO='N';
                        $cliente->MODIF_NOMB_EN_FAC='N';
                        $cliente->SALDO_TRANS='0';
                        $cliente->SALDO_TRANS_LOCAL='0';
                        $cliente->SALDO_TRANS_DOLAR='0';
                        $cliente->PERMITE_DOC_GP='N';
                        $cliente->PARTICIPA_FLUJOCAJA='N';
                        $cliente->CURP=NULL;
                        $cliente->USUARIO_CREACION= $tmaster->id_usuario;
                        $cliente->FECHA_HORA_CREACION =$diahora;
                        $cliente->USUARIO_ULT_MOD= $tmaster->id_usuario;
                        $cliente->FCH_HORA_ULT_MOD=$diahora;
                        $cliente->EMAIL_DOC_ELECTRONICO_COPIA=NULL;
                        $cliente->DETALLAR_KITS='N';
                        $cliente->XSLT_PERSONALIZADO=NULL;
                        $cliente->GEO_LATITUD=NULL;
                        $cliente->GEO_LONGITUD=NULL;
                        $cliente->DIVISION_GEOGRAFICA3=NULL;
                        $cliente->DIVISION_GEOGRAFICA4=NULL;
                        $cliente->OTRAS_SENAS=NULL;
                        $cliente->SUBTIPODOC='Factura';
                        $cliente->USA_API_RECEPCION='N';
                        $cliente->API_RECEPCION_DE=NULL;
                        $cliente->USER_API_RECEPCION=NULL;
                        $cliente->PASS_API_RECEPCION=NULL;
                        $cliente->TIPO_IMPUESTO=NULL;
                        $cliente->TIPO_TARIFA=NULL;
                        $cliente->PORC_TARIFA=NULL;
                        $cliente->TIPIFICACION_CLIENTE='05';
                        $cliente->AFECTACION_IVA='01';
                        $cliente->ES_EXTRANJERO='N';
                        $cliente->item_hacienda=NULL;
                        $cliente->U_TIPO_RECEPTOR=NULL;
                        $cliente->XSLT_PERSONALIZADO_CREDITO=NULL;
                        $cliente->USO_CFDI=NULL;
                        $cliente->TIPO_GENERAR=NULL;
                        $cliente->TIPO_PERSONERIA=NULL;
                        $cliente->METODO_PAGO=NULL;
                        $cliente->BANCO_NACION=NULL;
                        $cliente->ES_AGENTE_PERCEPCION='N';
                        $cliente->ES_BUEN_CONTRIBUYENTE='N';
                        $cliente->SUJETO_PORCE_SUNAT='N';
                        $cliente->NOMBRE_ADDENDA=NULL;
                        $cliente->PDB_EXPORTADORES='N';
                        $cliente->USA_MONTO_TOPE='N';
                        $cliente->MONTO_TOPE=NULL;
                        $cliente->U_COD_CRS=NULL;
                        $cliente->U_TIP_CLI=NULL;
                        $cliente->DETRACCION_AUTO='S';
                        $cliente->RECINTO_FISCAL=NULL;
                        $cliente->GENERA_GUIA_REMISION='N';
                        $cliente->AUTO_DETRACCION='N';
                        $cliente->GENERA_RECIBO_PAGO=NULL;


                        $cliente->save();



                        //////**************pasos para insertar correctamente el cliente. 4 Direccion_Embarque
                        ///select * from demo.DIRECC_EMBARQUE

                        $direccionembarq = new Direccion_Embarque();

                        $direccionembarq->CLIENTE =$request->DUI;
                        $direccionembarq->DIRECCION = 'ND';
                        $direccionembarq->DETALLE_DIRECCION =NULL;
                        $direccionembarq->DESCRIPCION =' ';
                        $direccionembarq->CONTACTO =NULL;
                        $direccionembarq->CARGO =NULL;
                        $direccionembarq->TELEFONO1 =NULL;
                        $direccionembarq->TELEFONO2 =NULL;
                        $direccionembarq->FAX =NULL;
                        $direccionembarq->EMAIL =NULL;

                        $direccionembarq->save();
                    

                        //////**************pasos para insertar correctamente el cliente. 5 cliente_vendedor
                        ////select * from demo.CLIENTE_VENDEDOR
                        $clientevendedor = new Cliente_Vendedor();
                        $clientevendedor->CLIENTE =$request->DUI;
                        $clientevendedor->VENDEDOR =$vend;
                        $clientevendedor->save();

                   // ✅ Si todo salió bien
                DB::commit();

                return response()->json([
                    'message' => 'Cliente creado correctamente',
                    'cliente' => $cliente,
                    'status'  => 201
                ], 201);
            } catch (\Throwable $e) {
               // ❌ Si algo falla, revertimos todo
                  DB::rollBack();


                return response()->json([
                    'message' => 'Error al guardar los datos: ' . $e->getMessage(),
                    'status'  => 500
                ], 500);
            }
            //     if( !$cliente){
            //             $data =[
            //                 'message'=> 'Error al crear el Cliente',
            //                 'estatus'=>500
            //         ];
            //         return response()->json($data ,500);
            //     };
                

            // $data =[
            //         'cliente' =>$cliente,
            //         'status'=>201             
            // ];
            // return response()->json($data ,500);


    }
}