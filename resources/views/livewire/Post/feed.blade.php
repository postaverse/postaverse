<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @include('livewire.Content.list', [
            'items' => $posts,
            'title' => 'Feed',
            'emptyTitle' => 'No posts in your feed',
            'emptyMessage' => 'Follow other users to see their posts here.',
            'component' => 'post',
            'componentProp' => 'post',
        ])
    </div>
</div>
