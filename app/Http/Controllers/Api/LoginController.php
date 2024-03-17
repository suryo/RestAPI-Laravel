<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use \Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $username = strtolower(addslashes($request->username));
        $password = $this->encrypt_decrypt('encrypt', $request->password);

        // dd($password);

        $user = User::where('username', $username)->first();

        if ($user) {
            if ($user->status == 1) {
            if ($user->migrasi == 0) {
                $userMigrasi = User::where('username', $username)
                    ->where('password', $password)
                    ->first();

                if ($userMigrasi) {
                    $newPassword = Hash::make($request->password);
                    $user->update(['password' => $newPassword, 'migrasi' => 1]);
                    $credentials = $request->only('username', 'password');

                    try {
                        if (!$token = JWTAuth::attempt($credentials)) {
                            return response()->json(['success' => false, 'message' => 'username atau password salah.'], 200);
                        }
                    } catch (JWTException $e) {
                        return response()->json(['success' => false, 'message' => 'Could not create token.'], 200);
                    }
                    $userAuth = auth()->user();
                    return response()->json(['success' => true, 'message' => 'Login successful', 'token' => $token, 'nama' => $userAuth->nama, 'jabatan' => $userAuth->roles[0]->name,'nik' => $userAuth->nik, 'departemen' => $userAuth->departemen, 'divisi' => $userAuth->divisi ]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Password tidak sesuai.']);
                }
            } else if ($user->migrasi == 1) {
                $credentials = $request->only('username', 'password');
                try {
                    if (!$token = JWTAuth::attempt($credentials)) {
                        return response()->json(['success' => false, 'message' => 'username atau password salah.'], 200);
                    }
                } catch (JWTException $e) {
                    return response()->json(['success' => false, 'message' => 'Could not create token.'], 200);
                }
                $userAuth = auth()->user();
                return response()->json(['success' => true, 'message' => 'Login successful', 'token' => $token, 'nama' => $userAuth->nama, 'jabatan' => $userAuth->roles[0]->name,'nik' => $userAuth->nik, 'departemen' => $userAuth->departemen, 'divisi' => $userAuth->divisi ]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'User tidak Aktif.']);
        }
        } else {
            return response()->json(['success' => false, 'message' => 'User tidak Ada.']);
        }
    }


    public function encrypt_decrypt($action, $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'osdkfje';
        $secret_iv = 'sdfvcdfeg';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a
        // warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else {
            if ($action == 'decrypt') {
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            }
        }

        return $output;
    }
}