<?php

namespace App\Services;

use App\Models\User\User;
use App\Models\User\Banned;
use App\Models\BannedIp;
use App\Models\Admin\AdminLogs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IpBanService
{
    /**
     * Ban a user and all their associated IP addresses
     */
    public function banUser(int $userId, string $reason, ?User $admin = null): array
    {
        $admin = $admin ?? Auth::user();
        
        // Check permissions
        if (!$admin || $admin->admin_rank < 3) {
            return ['success' => false, 'message' => 'Insufficient permissions to ban users.'];
        }

        $user = User::find($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        // Cannot ban admins of equal or higher rank
        if ($user->admin_rank > 0 && $user->admin_rank >= $admin->admin_rank) {
            return ['success' => false, 'message' => 'You cannot ban an admin with equal or higher rank.'];
        }

        // Get all IP addresses associated with this user from sessions
        $userIpAddresses = DB::table('sessions')
            ->where('user_id', $user->id)
            ->whereNotNull('ip_address')
            ->distinct()
            ->pluck('ip_address')
            ->toArray();

        // Create the primary ban record for the user
        $banned = $user->bans()->create([
            'reason' => $reason,
        ]);

        // Create banned IP records for each IP address used by the user
        $ipBanCount = 0;
        foreach ($userIpAddresses as $ipAddress) {
            $banned->bannedIps()->create([
                'ip_address' => $ipAddress,
            ]);
            $ipBanCount++;
        }

        // Log the action
        AdminLogs::create([
            'admin_id' => $admin->id,
            'action' => 'Banned user ' . $user->name . ' (ID: ' . $user->id . ') and ' . $ipBanCount . ' IP addresses',
        ]);

        return [
            'success' => true, 
            'message' => 'User banned successfully along with ' . $ipBanCount . ' associated IP addresses.',
            'ban_id' => $banned->id,
            'ip_count' => $ipBanCount
        ];
    }

    /**
     * Unban a user and remove all associated IP bans
     */
    public function unbanUser(int $userId, ?User $admin = null): array
    {
        $admin = $admin ?? Auth::user();
        
        // Check permissions
        if (!$admin || $admin->admin_rank < 3) {
            return ['success' => false, 'message' => 'Insufficient permissions to unban users.'];
        }

        $user = User::find($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        $bans = Banned::where('user_id', $user->id)->with('bannedIps')->get();
        
        if ($bans->count() === 0) {
            return ['success' => false, 'message' => 'User is not currently banned.'];
        }

        $totalIpBans = $bans->sum(function($ban) {
            return $ban->bannedIps->count();
        });
        
        // Delete all ban records for this user (this will cascade delete IP bans)
        Banned::where('user_id', $user->id)->delete();
        
        // Log the action
        AdminLogs::create([
            'admin_id' => $admin->id,
            'action' => 'Unbanned user ' . $user->name . ' (ID: ' . $user->id . ') and removed ' . $totalIpBans . ' IP bans',
        ]);

        return [
            'success' => true,
            'message' => 'User unbanned successfully. Removed ' . $bans->count() . ' ban records including ' . $totalIpBans . ' IP bans.',
            'ban_count' => $bans->count(),
            'ip_count' => $totalIpBans
        ];
    }

    /**
     * Check if an IP address is banned
     */
    public function isIpBanned(string $ipAddress): bool
    {
        return BannedIp::where('ip_address', $ipAddress)->exists();
    }

    /**
     * Check if a user is banned
     */
    public function isUserBanned(int $userId): bool
    {
        return Banned::where('user_id', $userId)->exists();
    }
}
