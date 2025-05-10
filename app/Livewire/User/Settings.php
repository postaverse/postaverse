<?php
namespace App\Livewire\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class Settings extends Controller
{
    /**
     * Show the user profile screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        
        // Make sure we have a verified user
        if (!$user || !$user->hasVerifiedEmail()) {
            return redirect('/login');
        }

        return view('profile.show', [
            'request' => $request,
            'user' => $user,
        ]);
    }
}