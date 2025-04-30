<div class="flex flex-col items-center justify-center">
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight mb-2">
            {{ __('You have been banned.') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-center justify-center main max-w-7xl">
        <div class="bg-gray-800/10 backdrop-blur-sm border border-white/20 overflow-hidden shadow-sm sm:rounded-lg p-4 text-center hover:border-white/30 transition-colors duration-200">
            <h1 class="text-xl font-bold text-white pb-4">Please contact an admin for more information.</h1>
            <h2 class="text-lg font-bold text-white pb-4">Reason:</h2>
            <p class="text-lg font-bold text-white pb-4">{{ $reason }}</p>
        </div>
    </div>
</div>
