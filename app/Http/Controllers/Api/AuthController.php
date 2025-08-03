<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request):JsonResponse{

        $validated= $request->validate([
            "name"=>"required|max:255",
            "email"=>"required|unique:users",
            "password"=> "required|min:8"
         ]);


         $user=User::create([
            "name"=> $validated["name"],
            "email"=> $validated["email"],
            "password"=> bcrypt($validated["password"]),
         ]);

         return response()->json([
            "message"=>"registration successfully"
         ]);

    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only("email", "password");
        // option 2: token authentication
       
        $user = User::where("email", $credentials["email"])->first();
        if(!$user||!Hash::check($credentials["password"], $user->password)){

            throw ValidationException::withMessages([
                "email" => ["The provided credentials are incorrect."],
            ]);
        }

        return response()->json([
            "token" => $user->createToken("auth_token")->plainTextToken,
            "user" => $user,
        ], 201); 
        /* option 1: sanctum SPA authentication
        if (Auth::attempt($credentials)) {
                        return response()->json(['message' => 'Logged in',],201);
        }
        return response()->json(['message' => 'Invalid credentials'], 401); */
    }

    public function user(Request $request){
        return $request->user();
    }
    public function logout(Request $request): JsonResponse{

        $request->user()->currentAccessToken()->delete();   

        return response()->json(['message'=>'Logged out']);

    }
}
