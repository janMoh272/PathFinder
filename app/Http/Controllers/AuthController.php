<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $request->validate([
        'name'=>'required|string|max:255',
        'email'=>'required|string|max:255|unique:users,email,',
        'password'=>'required|string|min:8|confirmed',
        ]);

         $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
         ]);

         $token=$user->createToken('auth_token')->plainTextToken;

              return response()->json(['user'=>$user,'token'=>$token,'message'=>'Welcome'],201);

    
    }



    public function login(Request $request){
     $request->validate([
        'email'=>'required|string|max:255',
        'password'=>'required|string|min:8',
     ]);
     if(!Auth::attempt($request->only('email','password'))){
        return response()->json(['message'=>'invalide password or email'],401);
     }

     $user=User::where('email',$request->email)->FirstOrFail();
     $token=$user->createToken('auth_token')->plainTextToken;
     return response()->json([
        'user'=>$user,
        'token'=>$token,
        'message'=>"welcome",
     ],201);




    }

    public function logout(Request $request){
      $request->user()->currentAccessToken()->delete();
       return response()->json(['message'=>'LOGOUT SUCCESSFULLY !'], 200);

    }
}
