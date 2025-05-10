<?php

namespace App\Http\Controllers;

use App\Models\User\PendingUser;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    /**
     * Handle the verification link.
     *
     * @param  int  $id
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($id, $token)
    {
        $pendingUser = PendingUser::findOrFail($id);
        
        if ($pendingUser->verification_token !== $token) {
            return redirect('/register')->with('error', 'Invalid verification link.');
        }
        
        // Check if the user already exists (rare case)
        if (User::where('email', $pendingUser->email)->orWhere('handle', $pendingUser->handle)->exists()) {
            $pendingUser->delete();
            return redirect('/login')->with('error', 'This account has already been created.');
        }
        
        // Create the actual user
        $user = User::create([
            'name' => $pendingUser->name,
            'handle' => $pendingUser->handle,
            'email' => $pendingUser->email,
            'password' => $pendingUser->password, // Already hashed
            'email_verified_at' => now(), // Mark as verified
        ]);
        
        // Ensure email is verified by explicitly setting it
        $user->forceFill(['email_verified_at' => now()])->save();
        
        // Delete the pending user
        $pendingUser->delete();
        
        return redirect('/login')->with('status', 'Your email has been verified. You can now log in with your credentials.');
    }
}
