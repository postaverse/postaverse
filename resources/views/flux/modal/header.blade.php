<div {{ $attributes->merge(['class' => 'flex items-center justify-between p-6 border-b border-zinc-700']) }}>
    <h3 class="text-lg font-semibold text-zinc-100">
        {{ $slot }}
    </h3>
</div>
