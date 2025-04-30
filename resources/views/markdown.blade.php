<x-app-layout>
    <x-slot name="header">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ $title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 main">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 prose prose-invert max-w-none">
                    {!! $content !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>