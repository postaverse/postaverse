<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <!-- Bulk Actions -->
                @if(!$notifications->isEmpty())
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <x-select 
                                    wire:model.live="bulkActionType" 
                                    class="pl-3 pr-10 py-2 text-base"
                                >
                                    <option value="">Bulk Actions</option>
                                    <option value="mark_read">Mark as Read</option>
                                    <option value="mark_unread">Mark as Unread</option>
                                    <option value="delete">Delete</option>
                                </x-select>
                            </div>

                            <button 
                                wire:click="executeBulkAction"
                                @if(empty($selectedNotifications) || empty($bulkActionType)) disabled @endif
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-indigo-600"
                            >
                                Apply
                            </button>
                            
                            @if($unreadCount > 0)
                                <button 
                                    wire:click="markAllAsRead" 
                                    class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors"
                                >
                                    Mark All as Read
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Notifications List -->
            @if($notifications->isEmpty())
                <div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <h2 class="text-xl font-bold text-white mb-2">No notifications yet</h2>
                    <p class="text-gray-400">When you get notifications, they'll show up here.</p>
                </div>
            @else
                <!-- Select All Row -->
                <div class="flex items-center p-4 mb-4 bg-gray-800/40 backdrop-blur-sm rounded-lg border border-white/10">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            wire:model.live="selectAll" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 bg-gray-700 border-gray-600 rounded"
                        >
                        <label class="ml-2 text-sm text-white">Select All</label>
                    </div>
                    <div class="ml-auto text-sm text-gray-400">
                        {{ count($selectedNotifications) }} selected
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="flex bg-linear-to-br {{ !$notification->read_at ? 'from-indigo-900/20 to-gray-800/60 border-indigo-500/30' : 'from-gray-900/80 to-gray-800/60 border-white/10' }} backdrop-blur-xl border overflow-hidden shadow-lg sm:rounded-xl p-4 transition-colors hover:border-white/20">
                            <!-- Checkbox -->
                            <div class="shrink-0 self-center mr-4">
                                <input 
                                    type="checkbox" 
                                    value="{{ $notification->id }}" 
                                    wire:model.live="selectedNotifications"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 bg-gray-700 border-gray-600 rounded"
                                >
                            </div>
                            
                            <!-- Notification Content -->
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <!-- User Info & Message -->
                                    <div class="flex space-x-4">
                                        <a href="{{ route('user-profile', $notification->user->id) }}" class="shrink-0">
                                            <img src="{{ $notification->user->profile_photo_url }}" 
                                                alt="{{ $notification->user->name }}'s profile photo" 
                                                class="w-12 h-12 rounded-full object-cover border-2 {{ !$notification->read_at ? 'border-indigo-500' : 'border-gray-700' }}">
                                        </a>
                                        <div>
                                            <div class="flex items-center">
                                                <p class="text-white font-medium">{{ $notification->message }}</p>
                                                @if(!$notification->read_at)
                                                    <span class="ml-2 bg-indigo-500 h-2 w-2 rounded-full"></span>
                                                @endif
                                            </div>
                                            <p class="text-gray-400 text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex space-x-4 mt-2">
                                                <a href="{{ $notification->link }}" class="text-indigo-400 hover:text-indigo-300 transition-colors text-sm">
                                                    View
                                                </a>
                                                
                                                @if($notification->read_at)
                                                    <button wire:click="markAsUnread({{ $notification->id }})" class="text-gray-400 hover:text-gray-300 transition-colors text-sm">
                                                        Mark as unread
                                                    </button>
                                                @else
                                                    <button wire:click="markAsRead({{ $notification->id }})" class="text-gray-400 hover:text-gray-300 transition-colors text-sm">
                                                        Mark as read
                                                    </button>
                                                @endif
                                                
                                                <button wire:click="deleteNotification({{ $notification->id }})" class="text-red-500 hover:text-red-400 transition-colors text-sm">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Indicator (for larger screens) -->
                                    <div class="hidden sm:block">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notification->read_at ? 'bg-gray-700 text-gray-400' : 'bg-indigo-800 text-indigo-100' }}">
                                            {{ $notification->read_at ? 'Read' : 'Unread' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
