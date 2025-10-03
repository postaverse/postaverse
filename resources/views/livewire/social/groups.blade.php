<div class="min-h-screen bg-zinc-900 text-zinc-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-zinc-100">Groups</h1>
                <p class="text-zinc-400 mt-1">Connect with communities that share your interests</p>
            </div>
            <flux:button wire:click="$set('showCreateModal', true)" variant="primary">
                <span class="flex items-center">
                    <flux:icon.plus class="w-4 h-4 mr-2"/>
                    Create Group
                </span>
            </flux:button>
        </div>

        <!-- My Groups Section -->
        @if($myGroups->count() > 0)
            <div class="mb-12">
                <h2 class="text-xl font-semibold text-zinc-100 mb-6">My Groups</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($myGroups as $group)
                        <a href="{{ route('s.show', $group->slug) }}" class="block bg-zinc-800 rounded-lg p-6 border border-zinc-700 hover:border-zinc-600 transition-colors">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-zinc-600 rounded-lg flex items-center justify-center">
                                    <flux:icon.users class="w-6 h-6 text-zinc-300"/>
                                </div>
                                <button wire:click.stop="leaveGroup({{ $group->id }})" class="text-red-400 hover:text-red-300 text-sm px-2 py-1 rounded hover:bg-red-500/10">
                                    Leave
                                </button>
                            </div>
                            
                            <h3 class="font-semibold text-zinc-100 mb-2">{{ $group->name }}</h3>
                            <p class="text-zinc-400 text-sm mb-4 line-clamp-2">{{ $group->description }}</p>
                            
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-zinc-500">{{ $group->members_count }} members</span>
                                <span class="text-xs text-zinc-600">
                                    @if($group->privacy === 'private')
                                        <flux:icon.lock class="w-3 h-3 inline mr-1"/>
                                        Private
                                    @elseif($group->privacy === 'secret')
                                        <flux:icon.lock class="w-3 h-3 inline mr-1"/>
                                        Secret
                                    @else
                                        Public
                                    @endif
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Discover Groups Section -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-zinc-100">Discover Groups</h2>
                <div class="w-64">
                    <flux:input 
                        wire:model.live="search" 
                        placeholder="Search groups..." 
                        class="bg-zinc-800 border-zinc-700 text-zinc-100"
                    >
                        <x-slot name="iconTrailing">
                            <flux:icon.magnifying-glass class="w-4 h-4 text-zinc-400"/>
                        </x-slot>
                    </flux:input>
                </div>
            </div>

            @if($allGroups->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($allGroups as $group)
                        @php
                            $isMember = auth()->user()->groups->contains($group->id);
                        @endphp
                        <div class="bg-zinc-800 rounded-lg border border-zinc-700 hover:border-zinc-600 transition-colors overflow-hidden">
                            <a href="{{ route('s.show', $group->slug) }}" class="block p-6 hover:bg-zinc-750">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-zinc-600 rounded-lg flex items-center justify-center">
                                        <flux:icon.users class="w-6 h-6 text-zinc-300"/>
                                    </div>
                                </div>
                                
                                <h3 class="font-semibold text-zinc-100 mb-2">{{ $group->name }}</h3>
                                <p class="text-zinc-400 text-sm mb-4 line-clamp-3">{{ $group->description }}</p>
                                
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-zinc-500">{{ $group->members_count }} members</span>
                                    <span class="text-xs text-zinc-600">
                                        @if($group->privacy === 'private')
                                            <flux:icon.lock class="w-3 h-3 inline mr-1"/>
                                            Private
                                        @elseif($group->privacy === 'secret')
                                            <flux:icon.lock class="w-3 h-3 inline mr-1"/>
                                            Secret
                                        @else
                                            Public
                                        @endif
                                    </span>
                                </div>
                            </a>
                            
                            @if(!$isMember)
                                <div class="px-6 pb-4">
                                    <flux:button 
                                        wire:click="joinGroup({{ $group->id }})" 
                                        size="sm" 
                                        variant="outline"
                                        class="w-full"
                                    >
                                        Join
                                    </flux:button>
                                </div>
                            @else
                                <div class="px-6 pb-4">
                                    <flux:badge color="green" class="w-full text-center">Joined</flux:badge>
                                </div>
                            @endif
                        </div>
                                <span class="text-zinc-500">{{ $group->members_count }} members</span>
                                <span class="text-xs text-zinc-600">
                                    @if($group->privacy === 'private')
                                        <flux:icon.lock class="w-3 h-3 inline mr-1"/>
                                        Private
                                    @elseif($group->privacy === 'secret')
                                        <flux:icon.lock class="w-3 h-3 inline mr-1"/>
                                        Secret
                                    @else
                                        Public
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $allGroups->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <flux:icon.users class="w-16 h-16 text-zinc-600 mx-auto mb-4"/>
                    <h3 class="text-lg font-medium text-zinc-300 mb-2">No groups found</h3>
                    <p class="text-zinc-500">
                        @if($search)
                            Try searching with different keywords
                        @else
                            Be the first to create a group!
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Group Modal -->
    @if($showCreateModal)
        <flux:modal wire:model="showCreateModal">
            <form wire:submit.prevent="createGroup">
                <flux:modal.header>Create New Group</flux:modal.header>
                
                <flux:modal.body class="space-y-6">
                    <flux:field>
                        <flux:label>Group Name</flux:label>
                        <flux:input wire:model="groupName" placeholder="Enter group name"/>
                        <flux:error name="groupName"/>
                    </flux:field>

                    <flux:field>
                        <flux:label>Description</flux:label>
                        <flux:textarea 
                            wire:model="groupDescription" 
                            placeholder="Describe what this group is about..."
                            rows="4"
                        />
                        <flux:error name="groupDescription"/>
                    </flux:field>

                    <flux:field>
                        <flux:checkbox wire:model="isPrivate" label="Make this group private"/>
                        <flux:description>Private groups require approval to join</flux:description>
                    </flux:field>
                </flux:modal.body>

                <flux:modal.footer>
                    <flux:button type="button" variant="ghost" wire:click="$set('showCreateModal', false)">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Create Group
                    </flux:button>
                </flux:modal.footer>
            </form>
        </flux:modal>
    @endif
</div>
