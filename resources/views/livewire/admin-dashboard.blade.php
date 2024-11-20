<div>
    <br>
    <x-slot name="header" class="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>
    <div class="flex flex-col items-center justify-center main">
        <br>
        <h1 class="text-xl font-bold text-white">Welcome, {{ auth()->user()->name }}</h1>
        <br>
        <p class="text-white">Rank: {{ auth()->user()->admin_rank }}</p>
        <br>
        <a href="/pulse"><h3 class="text-lg font-bold text-white">Pulse Dashboard</h3></a>
        <br>
        <!-- List of admins -->
        <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 text-center">
                <h1 class="text-4xl font-bold text-white pb-1">List of Admins</h1>
                <hr class="p-1">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-white mx-auto" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 w-1/3">Rank</th>
                                <th class="px-4 py-2 w-1/3">Name</th>
                                <th class="px-4 py-2 w-1/3">Profile</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr>
                                    <td class="border px-4 py-2">{{ $admin->admin_rank }}</td>
                                    <td class="border px-4 py-2">{{ $admin->name }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('user-profile', $admin->id) }}" class="hyperlink">
                                            View Profile
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if (auth()->user()->admin_rank == 4)
            <h1 class="text-4xl font-bold text-white pb-5">Rank 4</h1>
            <!-- Ban User: R4 -->
            <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 text-center">
                    <h1 class="text-4xl font-bold text-white pb-1">Ban User</h1>
                    <hr class="p-1">
                    <form wire:submit.prevent="banUser">
                        <div class="fixed-height-alert">
                            @if (session()->has('banmessage'))
                                <div class="text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">Success!</strong>
                                    <span class="block sm:inline">{{ session('banmessage') }}</span>
                                </div>
                            @else
                                <div class="text-red-700 px-4 py-4 rounded relative" role="alert">
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <x-label for="user_id" :value="__('User ID')" />
                            <x-input id="user_id" class="block mt-1 max-w-lg" type="text" name="user_id"
                                wire:model="user_id" required />
                            @error('user_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <br>
                            <x-label for="reason" :value="__('Reason')" />
                            <x-textarea id="reason" class="block mt-1" type="text" name="reason"
                                wire:model="reason" required />
                            @error('reason')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <x-button class="mt-4">
                                {{ __('Ban User') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Unban User: R4 -->
            <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 text-center">
                    <h1 class="text-4xl font-bold text-white pb-1">Unban User</h1>
                    <hr class="p-1">
                    <form wire:submit.prevent="unbanUser">
                        <div class="fixed-height-alert">
                            @if (session()->has('unbanmessage'))
                                <div class="text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">Success!</strong>
                                    <span class="block sm:inline">{{ session('unbanmessage') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <x-label for="uid" :value="__('User ID')" />
                            <x-input id="uid" class="block mt-1 max-w-lg" type="text" name="uid"
                                wire:model="uid" required />
                            @error('uid')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <x-button class="mt-4">
                                {{ __('Unban User') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Add Whitelisted Email: R4 -->
        @if (auth()->user()->admin_rank == 4)
            <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 text-center">
                    <h1 class="text-4xl font-bold text-white pb-1">Add Whitelisted Email</h1>
                    <hr class="p-1">
                    <form wire:submit.prevent="addEmail">
                        <div class="fixed-height-alert">
                            @if (session()->has('emailmessage'))
                                <div class="text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">Success!</strong>
                                    <span class="block sm:inline">{{ session('emailmessage') }}</span>
                                </div>
                            @else
                                <div class="text-red-700 px-4 py-4 rounded relative" role="alert">
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <x-label for="email" :value="__('Email')" />
                            <x-input id="email" class="block mt-1 max-w-lg" type="text" name="email"
                                wire:model="email" required />
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <x-button class="mt-4">
                                {{ __('Add Email') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Add Admin: R4 -->
        @if (auth()->user()->admin_rank == 4)
            <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 text-center">
                    <h1 class="text-4xl font-bold text-white pb-1">Add Admin</h1>
                    <hr class="p-1">
                    <form wire:submit.prevent="addAdmin">
                        <div class="fixed-height-alert">
                            @if (session()->has('addmessage'))
                                <div class="text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">Success!</strong>
                                    <span class="block sm:inline">{{ session('addmessage') }}</span>
                                </div>
                            @else
                                <div class="text-red-700 px-4 py-4 rounded relative" role="alert">
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <x-label for="admin_id" :value="__('Admin ID')" />
                            <x-input id="admin_id" class="block mt-1 max-w-lg" type="text" name="admin_id"
                                wire:model="admin_id" required />
                            @error('admin_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <br>
                            <x-label for="admin_rank" :value="__('Admin Rank')" />
                            <x-input id="admin_rank" class="block mt-1 max-w-lg" type="text" name="admin_rank"
                                wire:model="admin_rank" required />
                            @error('admin_rank')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <x-button class="mt-4">
                                {{ __('Add Admin') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Logs: R3, R4 -->
        <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 text-center">
                <h1 class="text-4xl font-bold text-white pb-1">Logs</h1>
                <hr class="p-1">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-white mx-auto" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 w-1/3">User</th>
                                <th class="px-4 py-2 w-1/3">Action</th>
                                <th class="px-4 py-2 w-1/3">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td class="border px-4 py-2">{{ $log->admin_id }}</td>
                                    <td class="border px-4 py-2">{{ $log->action }}</td>
                                    <td class="border px-4 py-2">{{ $log->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        .fixed-height-alert {
            height: 50px;
            /* Adjust the height as needed */
            overflow: hidden;
        }
    </style>
</div>
