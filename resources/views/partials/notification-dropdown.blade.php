<div x-show="open" @click.outside="open = false" x-cloak
    class="absolute mb-2 bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 z-50" style="width:200px;">

    <div class="p-3 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
        <flux:heading size="sm">Notifications</flux:heading>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('user.notifications.read') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs text-blue-500 hover:underline">Mark all read</button>
        </form>
        @endif
    </div>

    <div class="max-h-72 w-full overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-700">
        @forelse(auth()->user()->notifications->take(10) as $notification)
        <div class="p-3 {{ $notification->read_at ? 'opacity-60' : 'bg-green-200' }}">
            <p class="text-sm text-zinc-800 dark:text-zinc-600">
                {{ $notification->data['message'] }}
            </p>
            <p class="text-xs text-zinc-400 mt-1">
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>
        @empty
        <div class="p-4 text-center text-sm text-zinc-400">
            No notifications yet.
        </div>
        @endforelse
    </div>
</div>