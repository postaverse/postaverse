<?php

namespace App\Livewire;

use Livewire\Component;

class RedeemMeteors extends Component
{
    public $redeemed = false;
    public $lastRedeemed;

    public function redeemMeteors()
    {
        $user = auth()->user();
        $now = now();

        if (!$user->meteors_last_redeemed_at || $user->meteors_last_redeemed_at->diffInDays($now) >= 1) {
            $user->addMeteors(5);
            $user->meteors_last_redeemed_at = $now;
            $user->save();
            session()->flash('message', 'You have successfully redeemed your meteors! Reload the page to see the changes.');
            session(['user_has_redeemed_meteors' => true]); // Persistently store the redemption state
            $this->redeemed = true;
            $this->lastRedeemed = $user->meteors_last_redeemed_at;
        } else {
            $nextRedeemDate = $user->meteors_last_redeemed_at->addDay();
            $nextRedeemTime = $nextRedeemDate->copy(); // Keep the date part for displaying the time
            session()->flash("error", "You have already redeemed your meteors today. Please try again in " . str_replace(" from now", "", $nextRedeemTime->diffForHumans()) . ".");
            $this->lastRedeemed = $user->meteors_last_redeemed_at;
        }
    }

    public function render()
    {
        $user = auth()->user();
        $now = now();

        // Check if last redeemed date is not null and if it has been more than 1 day since the last redemption
        if ($user->meteors_last_redeemed_at && $user->meteors_last_redeemed_at->diffInDays($now) < 1) {
            $this->redeemed = true;
        } else {
            $this->redeemed = false;
        }

        $this->lastRedeemed = $user->meteors_last_redeemed_at;
        return view('livewire.redeem-meteors', ['redeemed' => $this->redeemed, 'lastRedeemed' => $this->lastRedeemed]);
    }
}