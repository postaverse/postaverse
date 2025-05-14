<?php

namespace App\Livewire\Content;

use Livewire\Component;
use App\Services\Profanity;
use App\Models\Interaction\Notification;
use Illuminate\Support\Facades\Auth;

abstract class BasePage extends Component
{
    public $comments;
    public $content = '';
    public $user;
    public $replyingTo = null;
    public $replyContent = '';

    protected function initializeBasePage(): void
    {
        $this->user = Auth::user();
    }

    public function startReply($commentId)
    {
        $this->replyingTo = $commentId;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyContent = '';
    }

    protected function getMentionedUsers($text)
    {
        preg_match_all('/@(\w+)/', $text, $matches);
        return \App\Models\User\User::whereIn('handle', $matches[1])
            ->orWhereIn('id', $matches[1])
            ->get();
    }

    protected function dispatchNotifications($mentionedUsers, $messageSuffix, $link)
    {
        foreach ($mentionedUsers as $mentionedUser) {
            if ($mentionedUser->id != $this->user->id) {
                Notification::create([
                    'user_id' => $mentionedUser->id,
                    'message' => $this->user->name . ' ' . $messageSuffix,
                    'link' => $link,
                ]);
            }
        }
    }

    protected function checkProfanity(string $text): bool
    {
        return (new Profanity())->hasProfanity($text);
    }
}
