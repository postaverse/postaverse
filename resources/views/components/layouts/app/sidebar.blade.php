<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full bg-zinc-900">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-900 text-zinc-100">
        <flux:sidebar sticky stashable class="border-e border-zinc-700 bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Social')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Home Feed') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('discover-people')" :current="request()->routeIs('discover-people')" wire:navigate>{{ __('Discover People') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('groups')" :current="request()->routeIs('groups')" wire:navigate>{{ __('Groups') }}</flux:navlist.item>
                    {{--
                    <flux:navlist.item icon="envelope" :href="route('messages')" :current="request()->routeIs('messages')" wire:navigate>{{ __('Messages') }}</flux:navlist.item>
                    --}}
                    <flux:navlist.item icon="bell" :href="route('notifications')" :current="request()->routeIs('notifications')" wire:navigate>{{ __('Notifications') }}</flux:navlist.item>
                    <flux:navlist.item icon="bookmark" :href="route('saved-posts')" :current="request()->routeIs('saved-posts')" wire:navigate>{{ __('Saved Posts') }}</flux:navlist.item>
                </flux:navlist.group>
                
                @if(auth()->user()->admin_level)
                <flux:navlist.group :heading="__('Admin')" class="grid">
                    <flux:navlist.item icon="cog" :href="route('admin.moderation')" :current="request()->routeIs('admin.moderation')" wire:navigate>{{ __('Moderation') }}</flux:navlist.item>
                    <flux:navlist.item icon="flag" :href="route('admin.moderation', ['filter' => 'reported'])" :current="request()->routeIs('admin.moderation') && request('filter') === 'reported'" wire:navigate>{{ __('Reports') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('admin.user-management')" :current="request()->routeIs('admin.user-management')" wire:navigate>{{ __('User Management') }}</flux:navlist.item>
                    <flux:navlist.item icon="chart-bar" :href="route('admin.analytics')" :current="request()->routeIs('admin.analytics')" wire:navigate>{{ __('Analytics') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            <!-- Quick Stats -->
            <div class="px-3 py-2 text-xs text-zinc-400">
                <div class="flex justify-between mb-1">
                    <span>Posts</span>
                    <span>{{ auth()->user()->posts()->count() }}</span>
                </div>
                <div class="flex justify-between mb-1">
                    <span>Followers</span>
                    <span>{{ auth()->user()->followers()->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Following</span>
                    <span>{{ auth()->user()->following()->count() }}</span>
                </div>
            </div>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->username"
                    :avatar="auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : null"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <img
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-zinc-700 text-zinc-300"
                                        src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : '' }}"
                                    />
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-zinc-100">{{ auth()->user()->username }}</span>
                                    <span class="truncate text-xs text-zinc-400">{{ '@' . auth()->user()->username }}</span>
                                    @if(auth()->user()->admin_level)
                                        <span class="truncate text-xs text-purple-400">{{ auth()->user()->admin_level_name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('user.profile', auth()->user()->username)" wire:navigate>
                            <flux:icon.user-circle variant="mini" class="mr-2" />
                            {{ __('My Profile') }}
                        </flux:menu.item>
                        <flux:menu.item :href="route('settings.profile')" wire:navigate>
                            <flux:icon.cog-6-tooth class="mr-2 w-4 h-4" />
                            {{ __('Settings') }}
                        </flux:menu.item>
                        <flux:menu.item href="#" wire:navigate>
                            <flux:icon.question-mark-circle variant="mini" class="mr-2" />
                            {{ __('Help & Support') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" class="w-full">
                            <flux:icon.arrow-right-start-on-rectangle variant="mini" class="mr-2" />
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :avatar="auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : null"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->username }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->display_name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('user.profile', auth()->user()->username)" wire:navigate>
                            <flux:icon.user-circle class="mr-2 w-4 h-4" />
                            {{ __('My Profile') }}
                        </flux:menu.item>
                        <flux:menu.item :href="route('settings.profile')" wire:navigate>
                            <flux:icon.cog-6-tooth class="mr-2 w-4 h-4" />
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" class="w-full">
                            <flux:icon.arrow-right-start-on-rectangle variant="mini" class="mr-2" />
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

    {{ $slot }}

    {{-- Mount global Livewire components that need to listen for events from anywhere --}}
    @livewire('report-form')

    @fluxScripts
    </body>
</html>
