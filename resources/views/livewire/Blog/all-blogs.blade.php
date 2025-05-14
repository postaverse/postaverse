<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
    @include('livewire.content.list', [
        'items' => $blogs,
        'title' => 'Blogs',
        'emptyTitle' => 'No blogs found',
        'emptyMessage' => 'It seems like there are no blogs available at the moment. Please check back later.',
        'component' => 'blog',
        'componentProp' => 'blog',
    ])
</div>
