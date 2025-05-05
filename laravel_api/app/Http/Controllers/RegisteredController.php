<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RegisteredController extends Controller
{
   
     public function store(Request $request)
     {
        try {
          
            //validation
           $attributes = $request->validate([
            'name'=> 'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
           ]);
    
            //create user
            $user = User::create($attributes);
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
            
    
        }//if catch any error  
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

     }

}
