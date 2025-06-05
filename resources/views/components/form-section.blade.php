@props(['submit' => null])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form @if ($submit) wire:submit="{{ $submit }}" @endif>
            <div
                class="bg-gray-800/10 backdrop-blur-sm border border-white/20 shadow-sm overflow-hidden {{ isset($actions) ? 'sm:rounded-md' : 'sm:rounded-md' }}">
                <div class="px-4 py-5">
                    <div class="grid grid-cols-6 gap-6">
                        {{ $form }}
                    </div>
                </div>

                @if (isset($actions))
                    <div class="flex items-center justify-end px-4 py-3 border-t border-white/20 text-end sm:px-6">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
