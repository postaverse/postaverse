<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
    <h2 class="text-2xl font-bold text-white mb-6 ml-1">Blogs</h2>

    @if ($blogs->isEmpty())
        <x-empty-state title="No blogs found"
            message="It seems like there are no blogs available at the moment. Please check back later." />
    @else
        <div class="space-y-6">
            @foreach ($blogs as $blog)
                <x-blog :blog="$blog" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $blogs->links() }}
        </div>
    @endif
</div>
