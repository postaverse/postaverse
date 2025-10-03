<div>
    @if($show)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-zinc-800 rounded-lg p-6 w-full max-w-md mx-4" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-zinc-100">Report Post</h3>
                    <button wire:click="$set('show', false)" class="text-zinc-400 hover:text-zinc-300">
                        ✕
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Reason</label>
                        <select wire:model="reason" class="w-full px-3 py-2 bg-zinc-700 text-zinc-100 rounded-lg border border-zinc-600">
                            <option value="spam">Spam</option>
                            <option value="harassment">Harassment</option>
                            <option value="hate_speech">Hate Speech</option>
                            <option value="violence">Violence</option>
                            <option value="adult_content">Adult Content</option>
                            <option value="copyright">Copyright</option>
                            <option value="fake_news">Fake News</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Description (optional)</label>
                        <textarea wire:model="description" rows="4" class="w-full px-3 py-2 bg-zinc-700 text-zinc-100 rounded-lg border border-zinc-600 resize-none"></textarea>
                    </div>

                    <div class="flex space-x-3">
                        <button wire:click="submit" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">Submit Report</button>
                        <button wire:click="$set('show', false)" class="bg-zinc-600 text-zinc-200 py-2 px-4 rounded-lg">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>