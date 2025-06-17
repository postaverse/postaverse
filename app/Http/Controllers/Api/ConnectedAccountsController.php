<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConnectedAccount;
use Illuminate\Http\Request;

class ConnectedAccountsController extends Controller
{
    /**
     * Get connected accounts for the user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $connectedAccounts = $user->connectedAccounts()->get()->map(function ($account) {
            return [
                'id' => $account->id,
                'provider' => $account->provider,
                'provider_id' => $account->provider_id,
                'name' => $account->name,
                'nickname' => $account->nickname,
                'email' => $account->email,
                'avatar' => $account->avatar_path,
                'created_at' => $account->created_at,
            ];
        });

        return response()->json([
            'connected_accounts' => $connectedAccounts,
        ]);
    }

    /**
     * Remove a connected account.
     */
    public function destroy(Request $request, $accountId)
    {
        $user = $request->user();
        
        $connectedAccount = $user->connectedAccounts()->findOrFail($accountId);
        $connectedAccount->delete();

        return response()->json([
            'message' => 'Connected account removed successfully.',
        ]);
    }
}
