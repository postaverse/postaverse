<div class="min-h-screen mt-10 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div>
        {{ $logo }}
    </div>

    <div
        class="w-full sm:max-w-lg mt-6 px-6 py-4 mb-10 bg-gray-800/10 backdrop-blur-sm border border-white/20 shadow-sm overflow-hidden sm:rounded-lg hover:border-white/30 transition-colors duration-200">
        {{ $slot }}
    </div>
</div>
