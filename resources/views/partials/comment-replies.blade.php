@props(['reply', 'replyingTo'])

<div class="bg-gray-800/30 backdrop-blur-sm border border-white/5 rounded-lg p-3 hover:border-white/10 transition-all mb-3">
    <!-- Reply User Info -->
    <div class="flex justify-between mb-3">
        <div class="flex space-x-3">
            <img src="{{ $reply->user->profile_photo_url }}"
                alt="{{ $reply->user->name }}'s profile photo" 
                class="w-8 h-8 rounded-full">
            <div>
                <h2 class="text-sm font-bold text-white flex items-center">
                    <a href="{{ route('user-profile', $reply->user->id) }}" class="hover:text-indigo-400 transition-colors">
                        {{ $reply->user->name }}
                    </a>
                    <x-admin-tag :user="$reply->user" />
                </h2>
                <p class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <!-- Reply Button -->
            @if (auth()->user())
                <button wire:click="startReply({{ $reply->id }})" class="text-indigo-400 hover:text-indigo-300 transition-colors text-sm flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    Reply
                </button>
            @endif
            
            <!-- Delete Button -->
            @if (auth()->user() && 
                ($reply->user_id == auth()->user()->id || 
                 $reply->post->user_id == auth()->user()->id || 
                 auth()->user()->admin_rank > 2))
                <button wire:click="deleteComment({{ $reply->id }})"
                    class="text-red-500 hover:text-red-400 transition-colors text-sm">
                    Delete
                </button>
            @endif
        </div>
    </div>
    
    <!-- Reply Content -->
    @if ($reply->has_profanity)
        @if (auth()->user()->profanity_block_type == 'hide_clickable')
            <div>
                <a href="#"
                    onclick="event.preventDefault(); this.nextElementSibling.style.display='block'; this.style.display='none'">
                    <p class="text-red-500 hover:underline text-sm">
                        Content hidden due to profanity. Click to reveal.
                    </p>
                </a>
                <div class="text-white prose prose-invert prose-sm mt-2" style="display:none;">
                    {!! $reply->content !!}
                </div>
            </div>
        @else
            <p class="text-red-500 text-sm">
                Content hidden due to profanity.
            </p>
        @endif
    @else
        <div class="text-white prose prose-invert prose-sm mt-2">{!! $reply->content !!}</div>
    @endif

    <!-- Reply Form -->
    @if (auth()->user() && $replyingTo === $reply->id)
        <div class="mt-4 pl-4 border-l-2 border-indigo-500/30">
            <form wire:submit.prevent="submitReply" class="w-full">
                <div class="mb-3">
                    <textarea 
                        id="replyContent-{{ $reply->id }}" 
                        class="w-full p-3 rounded-lg bg-gray-800/40 backdrop-blur-sm border border-white/10 text-white focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all text-sm" 
                        wire:model="replyContent"
                        placeholder="Write your reply here..."></textarea>
                    @error('replyContent')
                        <span class="error text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex space-x-2">
                    <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 transition-colors py-1 px-3 text-sm">
                        {{ __('Post Reply') }}
                    </x-button>
                    <button type="button" wire:click="cancelReply" class="text-gray-400 hover:text-gray-300 transition-colors text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif
    
    <!-- Nested Replies (recursion) -->
    @if ($reply->replies && $reply->replies->count() > 0)
        <div class="mt-4 pl-4 border-l border-indigo-500/20">
            @foreach ($reply->replies as $nestedReply)
                @include('partials.comment-replies', ['reply' => $nestedReply, 'replyingTo' => $replyingTo])
            @endforeach
        </div>
    @endif
</div>