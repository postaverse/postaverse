<div>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ $headerTitle }}
        </h2>
    </x-slot>

    <div class="flex flex-col items-center justify-center main py-8">
        {{ $slot }}
    </div>
</div>
