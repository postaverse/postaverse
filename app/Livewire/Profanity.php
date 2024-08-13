<?php

namespace App\Livewire;

use Livewire\Component;

class Profanity extends Component
{
    public function hasProfanity($string)
    {
        // cURL request to the Profanity API
        $url = 'https://kks.zanderlewis.dev/text/detect.php?input=' . urlencode($string);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the JSON response
        $response = json_decode($response, true);

        // Check if the response is valid and contains the 'profane' field
        if (is_array($response) && isset($response['profane'])) {
            return $response['profane'] === 1 ? 1 : 0;
        }

        // Default to returning 0 if the response is not valid or profane field is missing
        return 0;
    }

    public function render()
    {
        return view('livewire.profanity');
    }
}