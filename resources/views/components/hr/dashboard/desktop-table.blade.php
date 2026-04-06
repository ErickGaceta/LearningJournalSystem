<div class="hidden lg:block">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Training Name</flux:table.column>
                    <flux:table.column>Assigned Users</flux:table.column>
                    <flux:table.column align="center">Duration</flux:table.column>
                    <flux:table.column align="end">Status</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($modules ?? [] as $module)
                    <flux:table.row>
                        <flux:table.cell>{{ $module->title ?? 'N/A' }}</flux:table.cell>
                        <flux:table.cell>
                            {{ $module->assignments->count() ?? 0 }} user(s)
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            {{ $module->datestart->format('M d, Y') . ' - ' . $module->dateend->format('M d, Y') }}
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            @php
                            $now = now();
                            $start = $module->datestart;
                            $end = $module->dateend;
                            @endphp
                            @if ($now->lt($start))
                            <flux:badge color="amber" size="sm">Pending</flux:badge>
                            @elseif ($now->between($start, $end))
                            <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                            @elseif ($now->gt($end))
                            <flux:badge variant="solid" color="lime" size="sm">Completed</flux:badge>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                    @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center py-8">
                            <div class="text-neutral-500">No training modules found</div>
                        </flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>