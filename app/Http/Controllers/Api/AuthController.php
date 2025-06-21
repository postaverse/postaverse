<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'handle' => 'nullable|string|max:255|unique:users',
        ]);

        // Generate a unique handle if not provided
        $handle = $request->handle;
        if (!$handle) {
            $baseHandle = strtolower(str_replace(' ', '', $request->name));
            $handle = $baseHandle;
            $counter = 1;
            
            while (User::where('handle', $handle)->exists()) {
                $handle = $baseHandle . $counter;
                $counter++;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'handle' => $handle,
            'admin_rank' => 0,
            'bio' => '',
        ]);

        $token = $user->createToken('Mobile App')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'message' => 'Registration successful'
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // Can be email or handle
            'password' => 'required|string',
            'code' => 'nullable|string', // 2FA code
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('handle', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if 2FA is enabled and confirmed
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            // If no 2FA code provided, return challenge
            if (!$request->code) {
                return response()->json([
                    'two_factor' => true,
                    'recovery' => false,
                    'message' => 'Two-factor authentication code required.'
                ]);
            }

            // Verify 2FA code
            $twoFactorProvider = app(TwoFactorAuthenticationProvider::class);
            $valid = $twoFactorProvider->verify(
                decrypt($user->two_factor_secret),
                $request->code
            );

            if (!$valid) {
                // Check if it's a recovery code
                $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
                
                if (!in_array($request->code, $recoveryCodes)) {
                    throw ValidationException::withMessages([
                        'code' => ['The provided two-factor authentication code is invalid.'],
                    ]);
                }

                // Remove used recovery code
                $recoveryCodes = array_diff($recoveryCodes, [$request->code]);
                $user->two_factor_recovery_codes = encrypt(json_encode(array_values($recoveryCodes)));
                $user->save();
            }
        }

        $token = $user->createToken('Mobile App')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'message' => 'Login successful'
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get current user
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:500',
            'handle' => 'sometimes|string|max:255|unique:users,handle,' . $request->user()->id,
        ]);

        $user = $request->user();
        $user->update($request->only(['name', 'bio', 'handle']));

        return new UserResource($user->fresh());
    }
}
