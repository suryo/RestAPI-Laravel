<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use \Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function authenticate(Request $request)
     {
         
     }

     
    public function index(Request $request)
    {
        $credentials = $request->only('email', 'password');
 
         //valid credential
         $validator = Validator::make($credentials, [
             'email' => 'required|email',
             'password' => 'required'
         ]);
 
         //Send failed response if request is not valid
         if ($validator->fails()) {
             return response()->json([ 'success' => false,'message' => $validator->messages()], 200);
         }
 
     
 
         //Request is validated
         //Crean token
         try {
             if (!$token = JWTAuth::attempt($credentials)) {
                 return response()->json([
                     'success' => false,
                     'message' => 'email atau password salah.',
                 ], 200);
             }
         } catch (JWTException $e) {
             return $credentials;
             return response()->json([
                 'success' => false,
                 'message' => 'Could not create token.',
             ], 500);
         }
 
         // Check if user is verified
         $user = auth()->user();
 
        //  if (!$user->email_verified_at) {
        //      // User is not verified
        //      return response()->json([
        //          'success' => false,
        //          'message' => 'Silahkan verifikasi email anda terlebih dahulu.',
        //      ], 200);
        //  }
 
         // Token created, return with success response and JWT token
         return response()->json([
             'success' => true,
             'token' => $token,
             'name' => $user->name,
             'email' => $user->email,
         ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
