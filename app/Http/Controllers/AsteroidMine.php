<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AsteroidMine extends Controller
{
    // Asteroid Mine is a rust based meteor miner.
    // Receives a post request with the following data:
    // {
    //     "meteors": <number of meteors from mining>,
    //     "user_id": <user ID>,
    //     "password": <user password>,
    //     "checksum": <checksum>
    // }
    // It receives the meteors and updates the user's meteor count.
    public function __invoke(Request $request)
    {
        $userId = $request->input('user_id');
        $meteors = $request->input('meteors');
        $password = $request->input('password');
        $checksum = $request->input('checksum');

        // Log the received payload for debugging
        Log::info('Received payload', [
            'user_id' => $userId,
            'meteors' => $meteors,
            'password' => '[ CENSORED ]',
            'checksum' => $checksum,
        ]);

        // Validate the input
        if (is_null($userId) || is_null($meteors) || is_null($password) || is_null($checksum)) {
            return response()->json(['message' => 'Invalid input'], 400);
        }

        // Convert the user ID to a regular integer
        $userId = intval($userId);
        // Convert the meteors to a regular float
        $meteors = floatval($meteors);

        // Regenerate checksum
        $data = $meteors . $userId . $password;
        $generatedChecksum = hash('sha256', $data);

        // Log the generated checksum for debugging
        Log::info('Generated checksum', ['generated_checksum' => $generatedChecksum]);

        // Verify checksum
        if ($generatedChecksum !== $checksum) {
            return response()->json(['message' => 'Invalid checksum'], 400);
        }

        // Find the user
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Verify the user's password
        if (!password_verify($password, $user->password)) {
            return response()->json(['message' => 'Invalid password'], 400);
        }

        // Update the user's meteor count
        $user->addMeteors($meteors);

        // Log the meteor mining
        Log::info('User mined ' . $meteors . ' meteors', ['user_id' => $userId]);

        return response()->json(['message' => 'Meteors added successfully']);
    }
}