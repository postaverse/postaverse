<?php

namespace App\Livewire;

use Livewire\Component;

class Profanity extends Component
{
    public $enabled = 1
    public function hasProfanity($string)
    {
        if ($this->enabled == 0) {
        // cURL request to the Profanity API
        $url = 'https://kks.zanderlewis.dev/text/detect.php?input=' . urlencode($string);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the JSON response
        $response = json_decode($response, true);

        // Check the 'profane' field and return true/false
        
        return $response['profane'] === 1;
        } else {
            return
        }
    }

    public function render()
    {
        return view('livewire.profanity');
    }
}