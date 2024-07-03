<div>
    <br>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">
            <h1 class="text-xl font-bold text-white">
                Create a blog post
            </h1>
            <div class="w-full">
                <form wire:submit.prevent="create">
                    <div class="mb-4">
                        <label for="title" class="text-white">Title</label>
                        <input type="text" id="title" name="title" wire:model="title" class="w-full bg-gray-700 rounded-lg p-2 text-white">
                        @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="content" class="text-white">Content</label>
                        <textarea id="content" name="content" wire:model="content" class="w-full bg-gray-700 rounded-lg p-2 text-white"></textarea>
                        @error('content') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="bg-blue-500 text-white rounded-lg p-2">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>