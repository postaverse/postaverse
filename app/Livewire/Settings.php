<?php
namespace App\Livewire;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TextThemeUser;

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

        return view('profile.show', [
            'request' => $request,
            'user' => $user,
        ]);
    }
}