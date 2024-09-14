<div>
    <br>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Post') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-center justify-center main">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 main w-full">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
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
                <h1 class="text-xl font-bold text-white">
                    {{ $post->title }}
                </h1>
                <h3 class="text-base font-bold text-white">
                    {{ $post->created_at->diffForHumans() }}
                </h3>

                <p class="text-white bio-img">{!! $postContent !!}</p>

                @if (! empty($photos))
                    <div class="grid">
                        @foreach ($photos as $photo)
                            <a href="{{ Storage::url($photo->path) }}" target="_blank">
                                <img src="{{ Storage::url($photo->path) }}" alt="" loading="lazy" decoding="async" />
                            </a>
                        @endforeach
                    </div>
                @endif

                @if (auth()->user())
                    @if ($post->user_id == auth()->user()->id)
                        <button class="text-red-800 font-bold" wire:click="delete({{ $post->id }})">
                            Delete
                        </button>
                    @endif
                @endif
            </div>
        </div>
        <br>
        @if (auth()->user())
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 main w-full">
                @if ($post->likes->count() > 0)
                    <h2 class="text-2xl font-bold text-white pb-2 pl-2">Likes</h2>
                    <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                        <div class="flex items-center space-x-4">
                            <button wire:click="likePost({{ $post->id }})" class="text-white" id="likeButton">
                                @if (!$post->likes->contains('user_id', auth()->id()))
                                    <img src="{{ asset('images/unliked.png') }}" alt="Unlike" width="35"
                                        height="35" class="p-1">
                                @else
                                    <img src="{{ asset('images/liked.png') }}" alt="Like" width="35"
                                        height="35" class="p-1">
                                @endif
                            </button>
                            <div class="flex -space-x-4">
                                @foreach ($post->likes->take(10) as $like)
                                    <a href="{{ route('user-profile', $like->user->id) }}" class="hyperlink">
                                        <img src="{{ $like->user->profile_photo_url }}"
                                            alt="{{ $like->user->name }}'s profile photo"
                                            class="w-10 h-10 rounded-full border-2 border-gray-800">
                                    </a>
                                @endforeach
                                @if ($post->likes->count() > 10)
                                    <div
                                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-700 text-white border-2 border-gray-800">
                                        +{{ $post->likes->count() - 10 }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                <span class="text-white" id="likeCount-{{ $post->id }}">{{ $post->likes->count() }} likes</span>
                <br>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 main w-full">
            <h2 class="text-2xl font-bold text-white pb-2 pl-2">Comments</h2>
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                @if (auth()->user())
                    <div class="pb-3">
                        <form wire:submit.prevent="submit" class="max-w-7xl w-full">
                            <div class="mb-4 max-w-7xl w-full">
                                <x-label for="comment" value="{{ __('Comment') }}" />
                                <textarea id="comment" class="w-full p-2 rounded bg-gray-700 text-white" wire:model="content"
                                    placeholder="Write your comment here..."></textarea>
                                @error('content')
                                    <span class="error text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <x-button type="submit" class="bg-green-600"><img src="{{ asset('images/blastoff.png') }}"
                                    alt="Submit" width="35" height="35"
                                    class="p-1 pr-2">{{ __('Post') }}</x-button>
                            <script>
                                document.addEventListener('livewire:load', function() {
                                    Livewire.on('commentAdded', () => {
                                        document.getElementById('comment').value = '';
                                    });
                                });
                            </script>
                        </form>
                    </div>
                @endif
                <div>
                    @if ($comments)
                        @foreach ($comments as $comment)
                            <div class="bg-gray-700 p-2 rounded mt-2">
                                <div class="flex space-x-4">
                                    <img src="{{ $comment->user->profile_photo_url }}"
                                        alt="{{ $comment->user->name }}'s profile photo" class="w-8 h-8 rounded-full">
                                    <h2 class="text-lg font-bold text-white">
                                        <a href="{{ route('user-profile', $comment->user->id) }}" class="hyperlink">
                                            {{ $comment->user->name }}
                                        </a>
                                    </h2>
                                    @if ($comment->user->isSiteVerified())
                                        <img src="{{ asset('images/badges/verified.png') }}" alt="Verified"
                                            class="w-6 h-6">
                                    @endif
                                </div>
                                <p class="text-white">{!! $comment->content !!}</p>
                                <h3 class="text-base font-bold text-white">
                                    {{ $comment->created_at->diffForHumans() }}
                                </h3>
                            </div>
                        @endforeach
                    @else
                        <p class="text-white">No comments yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
</div>
