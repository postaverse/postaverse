@props(['adminRanks'])

<div class="bg-linear-to-br from-gray-900/80 to-gray-800/60 backdrop-blur-xl border border-white/10 overflow-hidden shadow-lg sm:rounded-xl p-6 hover:border-white/20 transition-all duration-300">
    <h2 class="text-2xl font-bold text-white mb-4">Add or Modify Admin</h2>

    @if (session()->has('addmessage'))
        <div class="mb-4 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('addmessage') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-400">
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="addAdmin" class="max-w-lg mx-auto">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <x-label for="admin_id" :value="__('User ID')" />
                <x-input id="admin_id" class="block mt-1 w-full" type="text"
                    name="admin_id" wire:model="admin_id" required />
                @error('admin_id')
                    <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <x-label for="admin_rank" :value="__('Admin Rank')" />
                <x-select id="admin_rank" wire:model="admin_rank">
                    <option value="">Select a rank</option>
                    @foreach ($adminRanks as $rank => $details)
                        @if ($rank > 0 && $rank < auth()->user()->admin_rank)
                            <option value="{{ $rank }}">{{ $rank }} - {{ $details['title'] }}</option>
                        @endif
                    @endforeach
                </x-select>
                @error('admin_rank')
                    <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <x-button class="w-full justify-center">
                    {{ __('Update Admin Status') }}
                </x-button>
            </div>
        </div>
    </form>
</div>
