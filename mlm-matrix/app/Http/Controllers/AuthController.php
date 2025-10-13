<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\PlacementService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(
        private PlacementService $placementService
    ) {}

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Find sponsor by referral code
            $sponsor = null;
            if (!empty($data['referral_code'])) {
                $sponsor = User::where('referral_code', $data['referral_code'])->first();
                
                if (!$sponsor) {
                    return response()->json([
                        'message' => 'Mã giới thiệu không hợp lệ.',
                        'errors' => ['referral_code' => ['Mã giới thiệu không tồn tại.']]
                    ], 422);
                }
            }
            
            // Create user
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'fullname' => $data['fullname'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'referred_by' => $sponsor?->id,
                'role' => 'member',
                'provider' => 'local',
                'active' => true,
            ]);
            
            // Place user in matrix
            $this->placementService->place($user, $sponsor);
            
            // Create token
            $token = $user->createToken('auth_token')->plainTextToken;
            
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'sponsor_id' => $sponsor?->id,
            ]);
            
            return response()->json([
                'message' => 'Đăng ký thành công.',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'fullname' => $user->fullname,
                    'referral_code' => $user->referral_code,
                ],
                'token' => $token,
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'data' => $request->validated(),
            ]);
            
            return response()->json([
                'message' => 'Đăng ký thất bại. Vui lòng thử lại.',
            ], 500);
        }
    }
    
    /**
     * Login user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            if (!Auth::attempt($data)) {
                return response()->json([
                    'message' => 'Email hoặc mật khẩu không chính xác.',
                ], 401);
            }
            
            $user = Auth::user();
            
            // Update last login time
            $user->updateLastLogin();
            
            // Create token
            $token = $user->createToken('auth_token')->plainTextToken;
            
            Log::info('User logged in', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            
            return response()->json([
                'message' => 'Đăng nhập thành công.',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'fullname' => $user->fullname,
                    'role' => $user->role,
                    'referral_code' => $user->referral_code,
                ],
                'token' => $token,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'error' => $e->getMessage(),
                'email' => $request->input('email'),
            ]);
            
            return response()->json([
                'message' => 'Đăng nhập thất bại. Vui lòng thử lại.',
            ], 500);
        }
    }
    
    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Revoke current token
            $user->currentAccessToken()->delete();
            
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            
            return response()->json([
                'message' => 'Đăng xuất thành công.',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Đăng xuất thất bại.',
            ], 500);
        }
    }
    
    /**
     * Get current user.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'fullname' => $user->fullname,
                'role' => $user->role,
                'referral_code' => $user->referral_code,
                'phone_number' => $user->phone_number,
                'active' => $user->active,
                'last_login_time' => $user->last_login_time,
            ],
        ]);
    }
    
    /**
     * Refresh token.
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Revoke current token
            $user->currentAccessToken()->delete();
            
            // Create new token
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'message' => 'Token đã được làm mới.',
                'token' => $token,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Token refresh failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            
            return response()->json([
                'message' => 'Làm mới token thất bại.',
            ], 500);
        }
    }
}