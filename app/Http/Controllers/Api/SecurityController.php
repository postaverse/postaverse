<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Sanctum\PersonalAccessToken;

class SecurityController extends Controller
{
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Enable two-factor authentication.
     */
    public function enableTwoFactor(Request $request)
    {
        $user = $request->user();

        if ($user->two_factor_secret) {
            return response()->json([
                'message' => 'Two-factor authentication is already enabled.',
            ], 400);
        }

        app(EnableTwoFactorAuthentication::class)($user);

        $qrCode = app(TwoFactorAuthenticationProvider::class)->qrCodeUrl(
            config('app.name'),
            $user->email,
            decrypt($user->two_factor_secret)
        );

        return response()->json([
            'qr_code' => $qrCode,
            'recovery_codes' => json_decode(decrypt($user->two_factor_recovery_codes), true),
        ]);
    }

    /**
     * Confirm two-factor authentication.
     */
    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();

        if (!$user->two_factor_secret) {
            return response()->json([
                'message' => 'Two-factor authentication is not enabled.',
            ], 400);
        }

        if ($user->two_factor_confirmed_at) {
            return response()->json([
                'message' => 'Two-factor authentication is already confirmed.',
            ], 400);
        }

        app(ConfirmTwoFactorAuthentication::class)($user, $request->code);

        return response()->json([
            'message' => 'Two-factor authentication confirmed successfully.',
        ]);
    }

    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_secret) {
            return response()->json([
                'message' => 'Two-factor authentication is not enabled.',
            ], 400);
        }

        app(DisableTwoFactorAuthentication::class)($user);

        return response()->json([
            'message' => 'Two-factor authentication disabled successfully.',
        ]);
    }

    /**
     * Get recovery codes.
     */
    public function getRecoveryCodes(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_recovery_codes) {
            return response()->json([
                'message' => 'Two-factor authentication is not enabled.',
            ], 400);
        }

        return response()->json([
            'recovery_codes' => json_decode(decrypt($user->two_factor_recovery_codes), true),
        ]);
    }

    /**
     * Regenerate recovery codes.
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_secret) {
            return response()->json([
                'message' => 'Two-factor authentication is not enabled.',
            ], 400);
        }

        app(GenerateNewRecoveryCodes::class)($user);

        return response()->json([
            'recovery_codes' => json_decode(decrypt($user->two_factor_recovery_codes), true),
        ]);
    }

    /**
     * Get browser sessions.
     */
    public function getBrowserSessions(Request $request)
    {
        $user = $request->user();
        $currentToken = $request->bearerToken();

        $sessions = PersonalAccessToken::where('tokenable_id', $user->id)
            ->where('tokenable_type', get_class($user))
            ->get()
            ->map(function ($token) use ($currentToken) {
                return [
                    'id' => $token->id,
                    'ip_address' => $token->last_used_at ? '127.0.0.1' : 'Unknown', // Would need to track this separately
                    'user_agent' => $token->name ?? 'Unknown Device',
                    'last_active' => $token->last_used_at ?? $token->created_at,
                    'is_current_device' => hash('sha256', $currentToken) === $token->token,
                ];
            });

        return response()->json([
            'sessions' => $sessions,
        ]);
    }

    /**
     * Logout other browser sessions.
     */
    public function logoutOtherSessions(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        $currentToken = $request->bearerToken();
        $currentTokenId = PersonalAccessToken::findToken($currentToken)?->id;

        // Delete all tokens except the current one
        PersonalAccessToken::where('tokenable_id', $user->id)
            ->where('tokenable_type', get_class($user))
            ->where('id', '!=', $currentTokenId)
            ->delete();

        return response()->json([
            'message' => 'Other browser sessions logged out successfully.',
        ]);
    }

    /**
     * Delete user account.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        // Delete all user tokens
        $user->tokens()->delete();

        // Delete the user account
        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully.',
        ]);
    }
}
