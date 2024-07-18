<div class="max-w-7xl mx-auto">
    <div>
        <button wire:click="redeemMeteors" class="bg-gray-400 hover:bg-gray-600 text-black font-bold py-2 px-4 rounded">
            <img src="{{ asset('images/meteor.png') }}" class="w-6 h-6 inline-block" alt="Meteor">
            @if (session()->has('error'))
            <span class="text-red-500">
            {{ session('error') }}
            </span>
            @elseif (session()->has('message'))
            <span class="text-green-500">
            {{ session('message') }}
            </span>
            @else
            Redeem your daily Meteors!
            @endif
        </button>
    </div>
</div>