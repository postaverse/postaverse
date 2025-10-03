<div class="min-h-screen bg-zinc-900 text-zinc-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-zinc-100">Notifications</h1>
                <p class="text-zinc-400 mt-1">Stay updated with your latest activity</p>
            </div>
            @if($unreadCount > 0)
                <flux:button wire:click="markAllAsRead" variant="outline">
                    Mark All Read
                </flux:button>
            @endif
        </div>

        <!-- Filter Tabs -->
        <div class="flex space-x-1 mb-8 bg-zinc-800 p-1 rounded-lg w-fit">
            <button 
                wire:click="$set('filter', 'all')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'all' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
            >
                All
            </button>
            <button 
                wire:click="$set('filter', 'unread')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'unread' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
            >
                Unread
                @if($unreadCount > 0)
                    <span class="ml-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                @endif
            </button>
            <button 
                wire:click="$set('filter', 'read')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $filter === 'read' ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-400 hover:text-zinc-200' }}"
            >
                Read
            </button>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-2">
                @foreach($notifications as $notification)
                    <div class="bg-zinc-800 rounded-lg p-4 border border-zinc-700 {{ !$notification->read_at ? 'bg-zinc-800/80' : 'bg-zinc-800/40' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- Icon based on notification type -->
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ !$notification->read_at ? 'bg-blue-600' : 'bg-zinc-600' }}">
                                    @switch($notification->type)
                                        @case('like')
                                            <flux:icon.heart class="w-5 h-5 text-white"/>
                                            @break
                                        @case('comment')
                                            <flux:icon.chat-bubble-left class="w-5 h-5 text-white"/>
                                            @break
                                        @case('follow')
                                            <flux:icon.user-plus class="w-5 h-5 text-white"/>
                                            @break
                                        @case('message')
                                            <flux:icon.envelope class="w-5 h-5 text-white"/>
                                            @break
                                        @default
                                            <flux:icon.bell class="w-5 h-5 text-white"/>
                                    @endswitch
                                </div>

                                <div class="flex-1">
                                    <div class="text-zinc-100 mb-1">
                                        {{ $notification->data['message'] ?? 'New notification' }}
                                    </div>
                                    <div class="text-sm text-zinc-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2 ml-4">
                                @if(!$notification->read_at)
                                    <flux:button 
                                        wire:click="markAsRead('{{ $notification->id }}')"
                                        size="sm"
                                        variant="ghost"
                                    >
                                        <flux:icon.check class="w-4 h-4"/>
                                    </flux:button>
                                @endif
                                
                                <div class="flex items-center gap-2">
                                    @if(!$notification->read_at)
                                        <flux:button size="sm" variant="ghost" wire:click="markAsRead('{{ $notification->id }}')" class="text-blue-400 hover:text-blue-300 hover:bg-blue-500/10">
                                            Mark as Read
                                        </flux:button>
                                    @endif
                                    <flux:button size="sm" variant="ghost" wire:click="deleteNotification('{{ $notification->id }}')" class="text-red-400 hover:text-red-300 hover:bg-red-500/10">
                                        Delete
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <flux:icon.bell class="w-16 h-16 text-zinc-600 mx-auto mb-4"/>
                <h3 class="text-lg font-medium text-zinc-300 mb-2">
                    @if($filter === 'unread')
                        No unread notifications
                    @elseif($filter === 'read')
                        No read notifications
                    @else
                        No notifications yet
                    @endif
                </h3>
                <p class="text-zinc-500">
                    @if($filter === 'all')
                        When people interact with your posts, you'll see notifications here
                    @else
                        Check back later for new activity
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
