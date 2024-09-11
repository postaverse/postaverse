<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EmojiCaptcha extends Component
{
    public array $emojis;
    public string $correctEmoji;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->emojis = ['😀', '😂', '😅', '😊', '😍', '😎', '😢', '😡', '😱', '🤔'];
        shuffle($this->emojis);
        $this->correctEmoji = $this->emojis[array_rand($this->emojis)];
        session(['correct_emoji' => $this->correctEmoji]);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.emoji-captcha', [
            'emojis' => $this->emojis,
            'correctEmoji' => $this->correctEmoji,
        ]);
    }
}