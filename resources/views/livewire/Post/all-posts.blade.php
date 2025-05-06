<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <h2 class="text-xl font-bold text-white mb-4">Recent Posts</h2>

        @if ($posts->isEmpty())
            <x-empty-state title="No posts found" />
        @else
            @foreach ($posts as $post)
                <div class="mb-6">
                    <x-post :post="$post" />
                </div>
            @endforeach

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <script>
        document.querySelectorAll('.likeButton').forEach(button => {
            const postId = button.dataset.postId;
            const countText = document.getElementById(`likeCount-${postId}`);

            button.addEventListener('click', function() {
                if (button.innerHTML.includes('unliked.png')) {
                    button.innerHTML =
                        '<img src="{{ asset('images/icons/like/liked.png') }}" alt="Like" width="20" height="20">';
                    countText.innerHTML = parseInt(countText.innerHTML) - 1 + ' likes';
                } else {
                    button.innerHTML =
                        '<img src="{{ asset('images/icons/like/unliked.png') }}" alt="Unlike" width="20" height="20">';
                    countText.innerHTML = parseInt(countText.innerHTML) + 1 + ' likes';
                }
            });
        });
    </script>
</div>
