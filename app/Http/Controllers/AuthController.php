<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\master_integraModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{


   public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


           $credentials = $request->only('email', 'password');
            $user = User::where('email', $request->email)->first();

        try {

                $user = User::where('email', $request->email)->first();
                  // Verificar si el usuario existe
                    if (! $user) {
                        return response()->json(['error' => 'Usuario no encontrado'], 404);
                    }
                  // Verificar si tiene el rol requerido
                       // Verificar si tiene el rol con ID 2 (tabla intermedia Spatie: model_has_roles)
                    $hasRole = DB::table('model_has_roles')
                        ->where('model_id', $user->id)
                        ->where('role_id', 2)
                        ->exists();

                        if (! $hasRole) {
                            return response()->json(['error' => 'No autorizado: rol inválido'], 403);
                        }
                 //   $credentials = request(['email', 'password']);

                  // if (! $token = auth()->attempt($credentials)) {
                if (! $token = JWTAuth::attempt($credentials)) {
                        return response()->json(['error' => 'Unauthorized'], 401);
                    }
                        return $this->respondWithToken($token,$user->name);
            } catch (JWTException $e) {
                return response()->json(['error' => 'Could not create token'], 500);
            }
    }

        
    protected function respondWithToken($token, $user)
    {
       // $employee = Employee::with('warehouse')->findOrFail($user->employee_id);
      // $datageneral = master_integraModel::all()->findOrFail($user->name);
        $datageneral = master_integraModel::where('id_usuario', $user)->firstOrFail();

        return response()->json([
            'access_token'   => $token,
            'logged_status'  => true,
            'bodega_id'   =>  $datageneral ->id_bodega,
            'Vendedor'  =>  $datageneral ->id_vendedor,
            'nivel_precio' => $datageneral ->nivel_precio,
            'token_type'     => 'bearer',     
            //'expires_in'     => 60 * 3,
            'expires_in'     => 80,
                //         'expires_in' => auth()->factory()->getTTL() * 720
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }



}
