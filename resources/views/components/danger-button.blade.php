<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-red-600/20 backdrop-blur-sm border border-red-500/30 rounded-md font-semibold text-xs text-red-300 uppercase tracking-widest hover:bg-red-600/30 hover:border-red-500/50 active:bg-red-700/30 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
