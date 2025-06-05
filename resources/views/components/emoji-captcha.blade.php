<div>
    <p class="text-xl">
        {!! __('Select this emoji:') !!} {{ $correctEmoji }}
    </p>
    <div class="flex flex-wrap space-x-2">
        @foreach ($emojis as $emoji)
            <button type="button" class="emoji-button text-2xl rounded-lg p-1"
                data-emoji="{{ $emoji }}">{{ $emoji }}</button>
        @endforeach
    </div>
    <input type="hidden" name="selected_emoji" id="selected_emoji">
</div>

<script>
    document.querySelectorAll('.emoji-button').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('selected_emoji').value = this.getAttribute('data-emoji');
            // Set the selected button's background color to gray when selected. Remove the bg from other emojis
            document.querySelectorAll('.emoji-button').forEach(button => {
                button.style.backgroundColor = '';
            });
            this.style.backgroundColor = 'gray';
        });
    });
</script>
