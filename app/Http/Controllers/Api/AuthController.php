<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(UserRequest $request){

        try {

            // Cari pengguna berdasarkan username
            $user = User::where('username', $request->username)->first();

            // Invalid Credentials (Jika data username tidak terdaftar)
            if(!$user) {
                return response([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Token
            $token = JWTAuth::fromUser($user);

            return response([
                'success' => true,
                'message' => 'Login Successfull',
                'data' => [
                    'user' => $user,
                    'token_type' => 'bearer',
                    'token' => $token,
                    'expires_in' => JWTAuth::factory()->getTTL() * 60,
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response([
                'status' => false,
                'message' => 'Could not create token' . $e
            ], 500);
        }
    }

    public function getUser(){
        try {
            // Current user
            $user = JWTAuth::user();

            return response([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Could not retrieve user',
            ], 500);
        }
    }

    public function refresh(){
        try {
            // Token refresh
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response([
                'success' => true,
                'message' => 'Token refreshed sucessfully',
                'data' => [
                    'token' => $newToken,
                    'expires_in' => JWTAuth::factory()->getTTL() * 60,
                ]
            ],200);
        } catch (\Throwable $e) {
            return response([
                'status' => false,
                'message' => 'Could not refresh token'
            ], 500);
        }
    }

    public function logout(){
        try {
            // Membatalkan token yang sedang aktif
            JWTAuth::invalidate(JWTAuth::getToken());

            return response([
                'success' => true,
                'message' => 'Logged out sucessfully'
            ],200);
        } catch (\Throwable $e) {
            return response([
                'status' => false,
                'message' => 'Could not log out'
            ], 500);
        }
    }
}
