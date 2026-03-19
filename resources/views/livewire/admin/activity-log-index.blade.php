<div class="space-y-4" wire:poll.30s>

    {{-- Filters --}}
    <div class="flex gap-3">
        <flux:input wire:model.live="search" placeholder="Search logs..." icon="magnifying-glass" class="grow" />
        <flux:select wire:model.live="action" class="grow">
            <option value="">All actions</option>
            <option value="login">Login</option>
            <option value="logout">Logout</option>
            <option value="created">Created</option>
            <option value="updated">Updated</option>
            <option value="deleted">Deleted</option>
        </flux:select>
    </div>

    {{-- Table --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column>User</flux:table.column>
            <flux:table.column>Action</flux:table.column>
            <flux:table.column>Description</flux:table.column>
            <flux:table.column>Model</flux:table.column>
            <flux:table.column>IP</flux:table.column>
            <flux:table.column>When</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @forelse($logs as $log)
            <flux:table.row :key="$log->id">
                <flux:table.cell>
                    <span class="text-sm font-medium">{{ $log->user?->full_name ?? 'System' }}</span>
                </flux:table.cell>
                <flux:table.cell>
                    @php
                        $actionColor = match($log->action) {
                            'login'   => 'green',
                            'logout'  => 'zinc',
                            'created' => 'blue',
                            'updated' => 'yellow',
                            'deleted' => 'red',
                            default   => 'zinc',
                        };
                    @endphp
                    <flux:badge color="{{ $actionColor }}" size="sm">{{ $log->action }}</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $log->description }}</span>
                </flux:table.cell>
                <flux:table.cell>
                    <span class="text-xs text-zinc-400">{{ $log->model ?? '—' }}</span>
                </flux:table.cell>
                <flux:table.cell>
                    <span class="text-xs text-zinc-400">{{ $log->ip_address }}</span>
                </flux:table.cell>
                <flux:table.cell>
                    <span class="text-xs text-zinc-400">{{ $log->created_at->diffForHumans() }}</span>
                </flux:table.cell>
            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="6" class="text-center text-sm text-zinc-400 py-8">
                    No activity found.
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <x-pagination :paginator="$logs" />
</div>