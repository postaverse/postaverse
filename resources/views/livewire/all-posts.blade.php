<div>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7453651531634667"
        crossorigin="anonymous"></script>
    <!-- Horizontal - Posts -->
    <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-7453651531634667"
        data-ad-slot="7105644688"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
    @foreach ($posts as $post)
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div wire:key="{{ $post->id }}" class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}'s profile photo"
                        class="w-10 h-10 rounded-full">
                    <h2 class="text-lg font-bold text-white">
                        <a href="{{ route('user-profile', $post->user->id) }}" class="hyperlink">
                            {{ $post->user->name }}
                        </a>
                    </h2>
                    @if ($post->user->isSiteVerified())
                        <img src="{{ asset('images/badges/verified.png') }}" alt="Verified" width="20"
                            height="20">
                    @endif
                </div>
                @if (auth()->user())
                    @if ($profanityOption == 'hide_clickable' && $checker->hasProfanity($post->title))
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="#"
                                    onclick="event.preventDefault(); this.nextElementSibling.style.display='block'; this.style.display='none'">
                                    <h1 class="text-xl font-bold text-red-500 hyperlink">
                                        Content hidden due to profanity. Click to reveal.
                                    </h1>
                                </a>
                                <h1 class="text-xl font-bold text-white" style="display:none;">
                                    {{ $post->title }}
                                </h1>
                                <h3 class="text-base font-bold text-white">
                                    {{ $post->created_at->diffForHumans() }}
                                </h3>
                            </div>
                            <a href="{{ route('post', $post->id) }}" class="text-white">
                                <img src="{{ asset('images/external-link.png') }}" alt="Go to post" width="20"
                                    height="20" style="filter: invert(1);">
                            </a>
                        </div>
                    @elseif($profanityOption == 'hide' && $checker->hasProfanity($post->title))
                        <h1 class="text-xl font-bold text-red-500">
                            Content hidden due to profanity.
                        </h1>
                    @else
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-xl font-bold text-white">
                                    {{ $post->title }}
                                </h1>
                                <h3 class="text-base font-bold text-white">
                                    {{ $post->created_at->diffForHumans() }}
                                </h3>
                            </div>
                            <a href="{{ route('post', $post->id) }}" class="text-white">
                                <img src="{{ asset('images/external-link.png') }}" alt="Go to post" width="20"
                                    height="20" style="filter: invert(1);">
                            </a>
                        </div>
                    @endif
                @else
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-white">
                                {{ $post->title }}
                            </h1>
                            <h3 class="text-base font-bold text-white">
                                {{ $post->created_at->diffForHumans() }}
                            </h3>
                        </div>
                        <a href="{{ route('post', $post->id) }}" class="text-white">
                            <img src="{{ asset('images/external-link.png') }}" alt="Go to post" width="20"
                                height="20" style="filter: invert(1);">
                        </a>
                    </div>
                @endif
                @if (auth()->user())
                    <div class="flex items-center space-x-4 pt-3 pb-3">
                        <button wire:click="likePost({{ $post->id }})" class="text-white" id="likeButton">
                            @if (!$post->likes->contains('user_id', auth()->id()))
                                <img src="{{ asset('images/unliked.png') }}" alt="Like" class="w-10 h-10">
                            @else
                                <img src="{{ asset('images/liked.png') }}" alt="Unlike" class="w-10 h-10">
                            @endif
                        </button>
                        <div class="flex -space-x-4">
                            @foreach ($post->likes->take(5) as $like)
                                <a href="{{ route('user-profile', $like->user->id) }}" class="hyperlink">
                                    <img src="{{ $like->user->profile_photo_url }}"
                                        alt="{{ $like->user->name }}'s profile photo"
                                        class="w-10 h-10 rounded-full border-2 border-gray-800">
                                </a>
                            @endforeach
                            @if ($post->likes->count() > 5)
                                <span
                                    class="text-white bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center">
                                    +{{ $post->likes->count() - 5 }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
                <!-- # of Comments -->
                <div class="flex items-center space-x-4">
                    <p class="text-white text-lg font-bold">
                        {{ $post->comments->count() }} comments
                    </p>
                </div>
                @if (auth()->user())
                    @if ($post->user_id == auth()->user()->id || auth()->user()->admin_rank >= 3)
                        <button class="text-red-800 font-bold" wire:click="delete({{ $post->id }})">
                            Delete
                        </button>
                    @endif
                @endif
            </div>
        </div>
    @endforeach

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        {{ $posts->links() }}
    </div>

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7453651531634667"
        crossorigin="anonymous"></script>
    <!-- Horizontal - Posts -->
    <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px"
        data-ad-client="ca-pub-7453651531634667" data-ad-slot="7105644688"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>

    <script>
        document.querySelectorAll('.likeButton').forEach(button => {
            const postId = button.dataset.postId;
            const countText = document.getElementById(`likeCount-${postId}`);

            button.addEventListener('click', function() {
                if (button.innerHTML.includes('unliked.png')) {
                    button.innerHTML =
                        '<img src="{{ asset('images/liked.png') }}" alt="Like" width="20" height="20">';
                    countText.innerHTML = parseInt(countText.innerHTML) - 1 + ' likes';
                } else {
                    button.innerHTML =
                        '<img src="{{ asset('images/unliked.png') }}" alt="Unlike" width="20" height="20">';
                    countText.innerHTML = parseInt(countText.innerHTML) + 1 + ' likes';
                }
            });
        });
    </script>
</div>
