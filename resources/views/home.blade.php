<x-app-layout>
    <x-slot name="header">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Home') }}
            </h2>
            @if (auth()->user())
                <livewire:redeem-meteors />
            @endif
        </div>
    </x-slot>

    <div class="py-12 main">
        <h1 class="text-2xl font-bold text-white" style="text-align:center;">Please Email <a
                href="mailto:zander@zanderlewis.dev">zander@zanderlewis.dev</a> your ideas instead of posting them.</h1>
        <livewire:create-post />
        <livewire:all-posts />
    </div>
</x-app-layout>
