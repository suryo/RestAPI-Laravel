<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //Request is validated, do logout        
        try {
            $validator = Validator::make($request->only('token'), [
                'token' => 'required'
            ]);


            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->messages()], 200);
            }



            $token = new \Tymon\JWTAuth\Token($request->token);
            \Tymon\JWTAuth\Facades\JWTAuth::manager()->invalidate($token, $forever = true);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], 200);
        }
    }
}