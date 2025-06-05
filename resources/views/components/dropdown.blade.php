@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-gray-800/10 backdrop-blur-sm border border-white/20',
    'dropdownClasses' => '',
])

@php
    switch ($align) {
        case 'left':
            $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
            break;
        case 'top':
            $alignmentClasses = 'origin-top';
            break;
        case 'none':
        case 'false':
            $alignmentClasses = '';
            break;
        case 'right':
        default:
            $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
            break;
    }

    switch ($width) {
        case '48':
            $width = 'w-48';
            break;
    }
@endphp

<div x-data="{ open: false, top: 0, left: 0, width: 0 }" x-init="$watch('open', value => {
    if (value) {
        const rect = $el.getBoundingClientRect();
        top = rect.bottom + window.scrollY;
        left = rect.left + window.scrollX;
        width = rect.width;
    }
})" @click.away="open = false" @close.stop="open = false" class="relative">

    <div @click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <template x-teleport="body">
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            :style="`position: absolute; top: ${top}px; left: ${left}px; width: ${width}px; z-index: 99999;`"
            class="dropdown-menu-portal {{ $width }} mt-2 rounded-md shadow-lg {{ $alignmentClasses }} {{ $dropdownClasses }}"
            style="display: none;" @click="open = false">
            <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
                {{ $content }}
            </div>
        </div>
    </template>
</div>
