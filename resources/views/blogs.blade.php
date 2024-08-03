<div>
    <x-slot name="header">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Blogs') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 main">
        @if ($user->admin_rank == 4)
            <livewire:create-blog />
        @endif
        <livewire:all-blogs />
    </div>
</div>
