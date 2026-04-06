<div class="lg:hidden space-y-4">
            @forelse($modules ?? [] as $module)
            @php
            $now = now();
            $start = $module->datestart;
            $end = $module->dateend;
            @endphp

            <flux:card class="p-4 bg-transparent">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold">{{ $module->title }}</span>
                        @if ($now->lt($start))
                        <flux:badge color="amber" size="sm">Pending</flux:badge>
                        @elseif ($now->between($start, $end))
                        <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                        @else
                        <flux:badge variant="solid" color="zinc" size="sm">Completed</flux:badge>
                        @endif
                    </div>

                    <flux:separator />

                    <div class="text-sm text-neutral-500 flex gap-2">
                        Dates: <flux:text variant="subtle">{{ $start->format('M d, Y') }} — {{ $end->format('M d, Y') }}</flux:text>
                    </div>
                    <div class="text-sm text-neutral-500 flex gap-2">
                        Assigned Users: <flux:text variant="subtle">{{ $module->assignments->count() }} user(s)</flux:text>
                    </div>
                </div>
            </flux:card>
            @empty
            <div class="text-center py-8 text-neutral-500">No training modules found</div>
            @endforelse
        </div>