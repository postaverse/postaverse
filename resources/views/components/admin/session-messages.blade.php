<!-- Success/Error Messages -->
@if (session()->has('banmessage'))
    <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 text-green-400">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <strong class="font-bold">User Banned Successfully!</strong>
                <p class="text-sm mt-1">{{ session('banmessage') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session()->has('unbanmessage'))
    <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 text-green-400">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <strong class="font-bold">User Unbanned Successfully!</strong>
                <p class="text-sm mt-1">{{ session('unbanmessage') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 text-red-400">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <strong class="font-bold">Error!</strong>
                <p class="text-sm mt-1">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if (session()->has('emailmessage'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('emailmessage') }}</span>
    </div>
@endif

@if (session()->has('addmessage'))
    <div class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('addmessage') }}</span>
    </div>
@endif
