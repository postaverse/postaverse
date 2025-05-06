<div>
    <x-slot name="header">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Blogs') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 main">
        @if (auth()->user())
            @if (auth()->user()->admin_rank >= 4)
                <livewire:blog.create-blog />
            @endif
        @endif
        <livewire:blog.all-blogs />
    </div>
</div>
