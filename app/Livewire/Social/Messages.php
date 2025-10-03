<?php

namespace App\Livewire\Social;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Messages extends Component
{
    public $selectedConversation = null;
    public $messageText = '';
    public $conversations = [];

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $this->conversations = auth()->user()->conversations()
            ->with(['participants', 'messages' => function($query) {
                $query->latest()->take(1);
            }])
            ->get()
            ->map(function($conversation) {
                $otherParticipant = $conversation->participants->where('id', '!=', auth()->id())->first();
                $lastMessage = $conversation->messages->first();
                
                return [
                    'id' => $conversation->id,
                    'other_user' => $otherParticipant,
                    'last_message' => $lastMessage,
                    'updated_at' => $conversation->updated_at
                ];
            })->sortByDesc('updated_at');
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversation = Conversation::with(['messages.sender', 'participants'])
            ->findOrFail($conversationId);
        
        // Mark messages as read
        $this->selectedConversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function sendMessage()
    {
        if (!$this->selectedConversation || !trim($this->messageText)) {
            return;
        }

        $message = $this->selectedConversation->messages()->create([
            'sender_id' => auth()->id(),
            'content' => $this->messageText,
        ]);

        $this->selectedConversation->touch();
        $this->messageText = '';
        $this->loadConversations();
        
        // Refresh the selected conversation
        $this->selectConversation($this->selectedConversation->id);
    }

    public function startNewConversation($userId)
    {
        $existingConversation = auth()->user()->conversations()
            ->whereHas('participants', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();

        if ($existingConversation) {
            $this->selectConversation($existingConversation->id);
            return;
        }

        $conversation = Conversation::create([
            'type' => 'direct'
        ]);

        $conversation->participants()->attach([auth()->id(), $userId]);
        
        $this->loadConversations();
        $this->selectConversation($conversation->id);
    }

    public function render()
    {
        return view('livewire.social.messages');
    }
}
