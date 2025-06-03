<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        @include('livewire.Content.list', [
            'items' => $posts,
            'title' => 'Recent Posts',
            'emptyTitle' => 'No posts found',
            'component' => 'post',
            'componentProp' => 'post',
        ])
    </div>
</div>
