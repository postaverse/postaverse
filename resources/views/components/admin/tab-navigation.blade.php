@props(['activeTab'])

<!-- Tab Navigation -->
<div class="border-b border-gray-700 mb-6">
    <nav class="flex flex-wrap -mb-px">
        <button wire:click="setActiveTab('overview')"
            class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
            Overview
        </button>
        <button wire:click="setActiveTab('admins')"
            class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'admins' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
            Admin Management
        </button>
        <button wire:click="setActiveTab('users')"
            class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'users' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
            User Management
        </button>
        @if (auth()->user()->admin_rank >= 3)
            <button wire:click="setActiveTab('ban')"
                class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'ban' ? 'border-red-500 text-red-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                Ban User
            </button>
            <button wire:click="setActiveTab('unban')"
                class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'unban' ? 'border-green-500 text-green-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                Unban User
            </button>
        @endif
        <button wire:click="setActiveTab('logs')"
            class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'logs' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
            Logs
        </button>
        @if (auth()->user()->admin_rank >= 4 && config('whitelisting.enabled'))
            <button wire:click="setActiveTab('whitelist')"
                class="mr-2 py-3 px-4 text-center border-b-2 font-medium text-sm {{ $activeTab === 'whitelist' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-400' }}">
                Email Whitelist
            </button>
        @endif
    </nav>
</div>
