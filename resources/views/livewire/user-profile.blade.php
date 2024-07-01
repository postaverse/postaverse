<div>
    <div class="user-profile">
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->bio }}</p>
        <div class="posts">
            @foreach ($posts as $post)
                <div class="post">
                    <h2>{{ $post->title }}</h2>
                    <p>{{ $post->content }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
