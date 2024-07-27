<div class="max-w-7xl mx-auto">
    <div>
        @if (session()->has('message'))
        <span class="text-green-500">
            {{ session('message') }}
        </span>
        @endif
        @if (!$redeemed)
        <button id="redeem-button" wire:click="redeemMeteors" class="bg-gray-400 hover:bg-gray-600 text-black font-bold py-2 px-4 rounded">
            <img src="{{ asset('images/meteor.png') }}" class="w-6 h-6 inline-block" alt="Meteor">
            Redeem your daily Meteors!
        </button>
        @endif
    </div>
    <script>
        // On redeemMeteor event, hide the button
        window.livewire.on('redeemMeteor', () => {
            const button = document.getElementById('redeem-button');
            if (button) {
                button.remove();
            }
        });
    </script>
</div>