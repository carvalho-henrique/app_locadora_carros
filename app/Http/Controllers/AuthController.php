<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $token = auth('api')->attempt($request->all(['email', 'password']));
        if($token){
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['erro' => 'Usuário ou senha inválido!'], 403);
        }
        return $token; 
    }
    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'Logout foi realizado com sucesso!']);
    }
    public function refresh(){
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }
    public function me(){
        return response()->json(auth()->user());
    }
}
