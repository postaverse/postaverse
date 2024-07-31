<div>
    @foreach ($posts as $post)
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div wire:key="{{ $post->id }}" class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
            <div class="flex items-center space-x-4">
                <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}'s profile photo" class="w-10 h-10 rounded-full">
                <h2 class="text-lg font-bold text-white">
                    <a href="{{ route('user-profile', $post->user->id) }}" class="hyperlink">
                        {{ $post->user->name }}
                    </a>
                </h2>
                @if ($post->user->isSiteVerified())
                <img src="{{ asset('images/badges/verified.png') }}" alt="Verified" width="20" height="20">
                @endif
            </div>
            @if($profanityOption == 'hide_clickable' && $post->hasProfanity)
            <a href="#" onclick="event.preventDefault(); this.nextElementSibling.style.display='block'; this.style.display='none'">
                <h1 class="text-xl font-bold text-red-500 hyperlink">
                    Content hidden due to profanity. Click to reveal.
                </h1>
            </a>
            <h1 class="text-xl font-bold text-white" style="display:none;">
                {{ $post->title }}
            </h1>
            @elseif($profanityOption == 'hide' && $post->hasProfanity)
            <h1 class="text-xl font-bold text-red-500">
                Content hidden due to profanity.
            </h1>
            @else
            <h1 class="text-xl font-bold text-white">
                {{ $post->title }}
            </h1>
            @endif
            <h3 class="text-base font-bold text-white">
                {{ $post->created_at->diffForHumans() }}
            </h3>
            @if($profanityOption == 'hide_clickable' && $post->hasProfanity)
            <p class="text-white cursor-pointer hyperlink" onclick="this.nextElementSibling.style.display='block'; this.style.display='none'">Content hidden due to profanity. Click to reveal.</p>
            <!-- Ensure this paragraph is initially hidden -->
            <div class="text-white" style="display:none;">{!! $parsedown->text(e($post->content)) !!}</div>
            @elseif($profanityOption == 'hide' && $post->hasProfanity)
            <p class="text-red-500">Content hidden due to profanity.</p>
            @else
            <p class="text-white">{!! $parsedown->text(e($post->content)) !!}</p>
            @endif

            <hr>

            <button wire:click="likePost({{ $post->id }})" class="text-white" id="likeButton">
                @if (!$post->likes->contains('user_id', auth()->id()))
                <img src="{{ asset('images/unliked.png') }}" alt="Unlike" width="35" height="35" class="p-1">
                @else
                <img src="{{ asset('images/liked.png') }}" alt="Like" width="35" height="35" class="p-1">
                @endif
            </button>
            <br>
            <span class="text-white" id="likeCount-{{ $post->id }}">{{ $post->likes->count() }} likes</span>
            <br>

            <hr>

            @if ($post->user_id == auth()->user()->id || auth()->user()->admin_rank >= 3)
            <button class="text-red-800 font-bold" wire:click="delete({{ $post->id }})">
                Delete
            </button>
            @endif
        </div>
    </div>
    @endforeach

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        {{ $posts->links() }}
    </div>

    <script>
        document.querySelectorAll('.likeButton').forEach(button => {
            const postId = button.dataset.postId;
            const countText = document.getElementById(`likeCount-${postId}`);
            
            button.addEventListener('click', function() {
                if (button.innerHTML.includes('unliked.png')) {
                    button.innerHTML = '<img src="{{ asset("images/liked.png") }}" alt="Like" width="20" height="20">';
                    countText.innerHTML = parseInt(countText.innerHTML) - 1 + ' likes';
                } else {
                    button.innerHTML = '<img src="{{ asset("images/unliked.png") }}" alt="Unlike" width="20" height="20">';
                    countText.innerHTML = parseInt(countText.innerHTML) + 1 + ' likes';
                }
            });
        });
    </script>
</div>