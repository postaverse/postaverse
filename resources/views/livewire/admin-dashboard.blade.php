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
        <!-- List of admins -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
                <h1 class="text-xl font-bold text-white">Admins</h1>
                <ul>
                    @foreach ($admins as $admin)
                    <li class="text-white font-bold">{{ $admin->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>