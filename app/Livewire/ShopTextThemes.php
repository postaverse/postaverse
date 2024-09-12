<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TextTheme;
use Illuminate\Support\Facades\Auth;
use App\Models\Meteor;

class ShopTextThemes extends Component
{
    use WithPagination;

    public function buyTheme($themeId)
    {
        $user = Auth::user();
        $theme = TextTheme::find($themeId);
    
        // Access the meteor quantity correctly
        $meteor = $user->meteorQuantity;
    
        if ($theme && $meteor->quantity >= $theme->meteorPrice && !$user->textThemes->contains($theme)) {
            // Deduct the meteor quantity using the Meteor model
            Meteor::where('user_id', $user->id)->decrement('quantity', $theme->meteorPrice);
            $user->textThemes()->attach($theme);
            session()->flash('message-' . $themeId, 'Text theme purchased successfully!');
        } elseif ($user->textThemes->contains($theme)) {
            session()->flash('error-' . $themeId, 'You already own this text theme!');
        }
        else {
            session()->flash('error-' . $themeId, 'You do not have enough meteors to purchase this text theme!');
        }
    }

    public function equipTheme($themeId)
    {
        $user = Auth::user();
        $theme = TextTheme::find($themeId);
    
        if ($theme && $user->textThemes->contains($theme)) {
            $user->textThemes()->updateExistingPivot($themeId, ['equipped' => 1]);
            // Remove equipped status from other themes
            $user->textThemes()->wherePivot('text_theme_id', '!=', $themeId)
            ->update(['equipped' => 0]);
            session()->flash('message', 'Text theme equipped successfully!');
        } else {
            session()->flash('error', 'You do not own this text theme!');
        }
    }

    public function unequipTheme($themeId)
    {
        $user = Auth::user();
        $theme = TextTheme::find($themeId);
    
        if ($theme && $user->textThemes->contains($theme)) {
            $user->textThemes()->updateExistingPivot($themeId, ['equipped' => 0]);
            session()->flash('message', 'Text theme unequipped successfully!');
        } else {
            session()->flash('error', 'You do not own this text theme!');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $themes = TextTheme::paginate(10);
        return view('livewire.shop-text-themes', ['themes' => $themes, 'user' => $user])->layout('layouts.app');
    }
}