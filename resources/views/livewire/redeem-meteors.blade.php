<div class="max-w-7xl mx-auto">
    <div class="text-center pb-1">
        @if (session()->has('message'))
        <div class="alert alert-success text-green-500">
            {{ session('message') }}
        </div>
        @elseif (session()->has('error'))
        <div class="alert alert-danger text-red-500">
            {{ session('error') }}
        </div>
        @endif
    </div>
    <div>
        <button wire:click="redeemMeteors" class="bg-gray-400 hover:bg-gray-600 text-black font-bold py-2 px-4 rounded">
            <img src="{{ asset('images/meteor.png') }}" class="w-6 h-6 inline-block" alt="Meteor">
            Redeem your daily Meteors!
        </button>
    </div>
</div>