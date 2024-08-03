<div class="container mx-auto p-4">
    <x-slot name="header">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Shop') }}
            </h2>
        </div>
    </x-slot>
    <h1 class="text-4xl font-bold mb-6 text-center">Username Themes</h1>
    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($themes as $theme)
            <li class="bg-gray-800 shadow-md rounded-lg p-6">
                @if ($theme->theme_name == 'wave')
                    <div class="wave pb-3">
                        <h2 class="text-2xl font-semibold mb-2 pl-10">{{ $theme->theme_name }}</h2>
                        <h2 class="text-2xl font-semibold mb-2 pl-10">{{ $theme->theme_name }}</h2>
                    </div>
                @elseif ($theme->theme_name == 'flipped')
                    <div class="txt">
                        <h1 data-text="{{ $theme->theme_name }}" class="flipped text-2xl mb-2">{{ $theme->theme_name }}
                        </h1>
                    </div>
                @else
                    <h2 class="text-2xl font-semibold mb-2 {{ $theme->class_name }}"
                        data-text="{{ $theme->theme_name }}">{{ $theme->theme_name }}</h2>
                @endif
                @if (session()->has('message-{{ $theme->id }}'))
                    <div class="text-green-500">
                        {{ session('message') }}
                    </div>
                @elseif (session()->has('error-{{ $theme->id }}'))
                    <div class="text-red-500">
                        {{ session('error') }}
                    </div>
                @endif
                <p class="text-gray-300 mb-4">Price: {{ $theme->meteorPrice }} meteors</p>
                @if ($user->hasTextTheme($theme->id))
                    @php
                        $textTheme = $user->textThemes->firstWhere('id', $theme->id);
                    @endphp
                    @if ($textTheme && $textTheme->pivot->equipped == 1)
                        <p class="text-green-500">Equipped</p>
                    @else
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                            wire:click="equipTheme({{ $theme->id }})">Equip</button>
                    @endif
                @else
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        wire:click="buyTheme({{ $theme->id }})">Buy</button>
                @endif
            </li>
        @endforeach
    </ul>
    <div class="mt-6">
        {{ $themes->links() }}
    </div>
</div>
