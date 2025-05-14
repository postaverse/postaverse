@php
    $view = 'components.' . $component;
@endphp
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
    <h2 class="text-2xl font-bold text-white mb-6 ml-1">{{ $title }}</h2>

    @if ($items->isEmpty())
        <x-empty-state :title="$emptyTitle" @isset($emptyMessage) :message="$emptyMessage" @endisset />
    @else
        <div class="space-y-6">
            @foreach ($items as $item)
                @include($view, [$componentProp => $item])
            @endforeach
        </div>

        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @endif
</div>
