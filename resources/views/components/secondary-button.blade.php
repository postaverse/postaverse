<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800/10 backdrop-blur-sm border border-white/20 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-800/20 hover:border-white/30 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
