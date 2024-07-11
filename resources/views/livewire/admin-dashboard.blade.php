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
        <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4 text-center">
                <h1 class="text-4xl font-bold text-white pb-1">List of Admins</h1>
                <hr class="p-1">
                <ul>
                    <!-- Sort by admin rank -->
                    <!-- Rank 1 -->
                    <li class="text-white font-bold text-2xl pb-1">Rank 1</li>
                    <p>- - - - - - - - - - -</p>
                    <ul>
                        @foreach ($admins as $admin)
                        @if ($admin->admin_rank == 1)
                        <li>
                            <a href="{{ route('user-profile', $admin->id) }}" class="hyperlink">
                                {{ $admin->name }}
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    <!-- Rank 2 -->
                    <li class="text-white font-bold text-2xl pb-1">Rank 2</li>
                    <p>- - - - - - - - - - -</p>
                    <ul>
                        @foreach ($admins as $admin)
                        @if ($admin->admin_rank == 2)
                        <li>
                            <a href="{{ route('user-profile', $admin->id) }}" class="hyperlink">
                                {{ $admin->name }}
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    <!-- Rank 3 -->
                    <li class="text-white font-bold text-2xl pb-1">Rank 3</li>
                    <p>- - - - - - - - - - -</p>
                    <ul>
                        @foreach ($admins as $admin)
                        @if ($admin->admin_rank == 3)
                        <li>
                            <a href="{{ route('user-profile', $admin->id) }}" class="hyperlink">
                                {{ $admin->name }}
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    <!-- Rank 4 -->
                    <li class="text-white font-bold text-2xl pb-1">Rank 4</li>
                    <p>- - - - - - - - - - -</p>
                    <ul>
                        @foreach ($admins as $admin)
                        @if ($admin->admin_rank == 4)
                        <li>
                            <a href="{{ route('user-profile', $admin->id) }}" class="hyperlink">
                                {{ $admin->name }}
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </ul>
            </div>
        </div>
    </div>
</div>