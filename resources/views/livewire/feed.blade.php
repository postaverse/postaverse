<div>
    @foreach($posts as $post)
        <div class="post">
            <h3>{{ $post->title }}</h3>
            <p>{{ $post->body }}</p>
        </div>
    @endforeach

    {{ $posts->links() }}
</div>