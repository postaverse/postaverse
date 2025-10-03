<div class="min-h-screen bg-zinc-900 text-zinc-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-zinc-800 rounded-lg shadow-xl overflow-hidden">
            <div class="flex h-[600px]">
                <!-- Conversations List -->
                <div class="w-1/3 border-r border-zinc-700">
                    <div class="p-4 border-b border-zinc-700">
                        <h2 class="text-xl font-bold text-zinc-100">Messages</h2>
                        <flux:button size="sm" class="mt-2 w-full" variant="outline">
                            <flux:icon.plus class="w-4 h-4 mr-2"/>
                            New Message
                        </flux:button>
                    </div>
                    
                    <div class="overflow-y-auto h-full">
                        @foreach($conversations as $conversation)
                            <div wire:click="selectConversation({{ $conversation['id'] }})" 
                                 class="p-4 border-b border-zinc-700/50 hover:bg-zinc-700/50 cursor-pointer transition-colors {{ $selectedConversation && $selectedConversation->id == $conversation['id'] ? 'bg-zinc-700' : '' }}">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-zinc-600 rounded-full flex items-center justify-center">
                                        <flux:icon.user class="w-5 h-5 text-zinc-300"/>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-zinc-100">
                                            {{ $conversation['other_user']->username ?? 'Unknown User' }}
                                        </div>
                                        @if($conversation['last_message'])
                                            <div class="text-sm text-zinc-400 truncate">
                                                {{ Str::limit($conversation['last_message']->content, 40) }}
                                            </div>
                                            <div class="text-xs text-zinc-500 mt-1">
                                                {{ $conversation['last_message']->created_at->diffForHumans() }}
                                            </div>
                                        @else
                                            <div class="text-sm text-zinc-500">No messages yet</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($conversations->isEmpty())
                            <div class="p-8 text-center">
                                <flux:icon.envelope class="w-12 h-12 text-zinc-600 mx-auto mb-4"/>
                                <h3 class="text-lg font-medium text-zinc-300 mb-2">No conversations yet</h3>
                                <p class="text-zinc-500">Start a conversation by discovering new people!</p>
                                <flux:button href="/discover-people" size="sm" class="mt-4">
                                    Find People
                                </flux:button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 flex flex-col">
                    @if($selectedConversation)
                        <!-- Chat Header -->
                        <div class="p-4 border-b border-zinc-700 bg-zinc-800/50">
                            @php
                                $otherUser = $selectedConversation->participants->where('id', '!=', auth()->id())->first();
                            @endphp
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-zinc-600 rounded-full flex items-center justify-center">
                                    <flux:icon.user class="w-5 h-5 text-zinc-300"/>
                                </div>
                                <div>
                                    <h3 class="font-medium text-zinc-100">{{ $otherUser->username ?? 'Unknown User' }}</h3>
                                    <div class="text-sm text-zinc-500">Active now</div>
                                </div>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-4">
                            @foreach($selectedConversation->messages->reverse() as $message)
                                <div class="flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-zinc-700 text-zinc-100' }}">
                                        <div class="text-sm">{{ $message->content }}</div>
                                        <div class="text-xs mt-1 {{ $message->sender_id == auth()->id() ? 'text-blue-200' : 'text-zinc-400' }}">
                                            {{ $message->created_at->format('g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Message Input -->
                        <div class="p-4 border-t border-zinc-700">
                            <form wire:submit.prevent="sendMessage" class="flex space-x-2">
                                <flux:input 
                                    wire:model="messageText" 
                                    placeholder="Type a message..." 
                                    class="flex-1 bg-zinc-700 border-zinc-600 text-zinc-100"
                                />
                                <flux:button type="submit" :disabled="!trim($messageText)">
                                    <flux:icon.envelope class="w-4 h-4"/>
                                </flux:button>
                            </form>
                        </div>
                    @else
                        <!-- No Conversation Selected -->
                        <div class="flex-1 flex items-center justify-center">
                            <div class="text-center">
                                <flux:icon.envelope class="w-16 h-16 text-zinc-600 mx-auto mb-4"/>
                                <h3 class="text-xl font-medium text-zinc-300 mb-2">Select a conversation</h3>
                                <p class="text-zinc-500">Choose a conversation from the sidebar to start messaging</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
