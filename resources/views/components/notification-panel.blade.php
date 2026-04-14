<div x-data="{ open: false }"
    @toggle-notifications.window="open = !open"
    @click.window="open = false"
    @keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    @click.stop
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="fixed bottom-16 left-4 w-80 bg-white dark:bg-zinc-900 rounded-xl shadow-xl border border-zinc-200 dark:border-zinc-700/60 z-50 overflow-hidden">

    {{-- Header --}}
    <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-700/60 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <flux:icon.bell class="w-4 h-4 text-zinc-500 dark:text-zinc-400" />
            <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">Notifications</span>
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('user.notifications.read') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
                Mark all read
            </button>
        </form>
        @endif
    </div>

    {{-- Notification List --}}
    <div class="max-h-80 overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-700/50">
        @forelse(auth()->user()->notifications->take(10) as $notification)
        <div class="relative flex gap-3 px-4 py-3 transition-colors
            {{ $notification->read_at
                ? 'bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/50'
                : 'bg-blue-50/50 dark:bg-blue-500/5 hover:bg-blue-50 dark:hover:bg-blue-500/10' }}">

            @unless($notification->read_at)
            <span class="absolute right-4 top-4 w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
            @endunless

            <div class="flex-1 min-w-0 pr-3">
                <p class="text-sm leading-snug
                    {{ $notification->read_at
                        ? 'text-zinc-500 dark:text-zinc-400'
                        : 'text-zinc-800 dark:text-zinc-100 font-medium' }}">
                    {{ $notification->data['message'] }}
                </p>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">
                    {{ $notification->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-10 text-center gap-2">
            <flux:icon.bell-slash class="w-7 h-7 text-zinc-300 dark:text-zinc-600" />
            <p class="text-sm text-zinc-400 dark:text-zinc-500">You're all caught up!</p>
        </div>
        @endforelse
    </div>
</div>