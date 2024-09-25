<div>
    {{--
    <div class="flex items-center justify-center mb-6">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7453651531634667"
            crossorigin="anonymous"></script>
        <!-- Horizontal - Posts -->
        <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px"
            data-ad-client="ca-pub-7453651531634667" data-ad-slot="7105644688"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    --}}
    @foreach ($posts as $post)
        <x-post :post="$post" />
    @endforeach

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        {{ $posts->links() }}
    </div>

    {{--
    <div class="flex items-center justify-center">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7453651531634667"
            crossorigin="anonymous"></script>
        <!-- Horizontal - Posts -->
        <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px"
            data-ad-client="ca-pub-7453651531634667" data-ad-slot="7105644688"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    --}}

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
