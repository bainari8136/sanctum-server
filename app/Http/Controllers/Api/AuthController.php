<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

         return response()->json([]);

    }
}
