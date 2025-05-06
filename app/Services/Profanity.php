<?php

namespace App\Services;

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

        // Check if cURL request was successful
        if ($response === false) {
            // cURL error occurred
            return 0; // Default to 0 if there's an error with the request
        }

        // Decode the JSON response
        $response = json_decode($response, true);

        // Check if the JSON decoding was successful
        if ($response === null) {
            // JSON decoding failed, you can also log or debug this
            return 0; // Default to 0 if the response is null
        }

        // Debugging output
        // var_dump($response); // Remove or comment this out in production

        // Check if the response contains the 'profane' field
        if (isset($response['profane'])) {
            return $response['profane'] === 1 ? 1 : 0;
        }

        // Default to returning 0 if the response is not valid or 'profane' field is missing
        return 0;
    }

    public function render()
    {
        return view('livewire.profanity');
    }
}