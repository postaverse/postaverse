@props(['user'])

@if ($user && $user->admin_rank > 0)
    <span
        class="inline-flex items-center px-2 py-0.5 ml-1 rounded-full text-xs font-medium ml-2
        @if ($user->admin_rank == 1) bg-blue-500/20 text-blue-300
        @elseif($user->admin_rank == 2) bg-green-500/20 text-green-300
        @elseif($user->admin_rank == 3) bg-purple-500/20 text-purple-300
        @elseif($user->admin_rank == 4) bg-indigo-500/20 text-indigo-300
        @elseif($user->admin_rank == 5) bg-red-500/20 text-red-300 @endif">
        @if ($user->admin_rank == 1)
            Junior Mod
        @elseif($user->admin_rank == 2)
            Mod
        @elseif($user->admin_rank == 3)
            Senior Mod
        @elseif($user->admin_rank == 4)
            Administrator
        @elseif($user->admin_rank == 5)
            Owner
        @endif
    </span>
@endif
