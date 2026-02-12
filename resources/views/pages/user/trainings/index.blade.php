<x-layouts::app :title="__('My Trainings')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="space-y-1">
            <flux:heading size="xl">{{ $user->first_name }}'s Training Viewer</flux:heading>
            <flux:subheading>See assigned trainings to you</flux:subheading>
        </div>

        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Title</flux:table.column>
                    <flux:table.column>Duration</flux:table.column>
                    <flux:table.column align="center">Status</flux:table.column>
                    <flux:table.column align="end" style="max-width:60px;">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($trainings as $tr)
                    <flux:table.row>
                        <flux:table.cell>{{ $tr->module->title }}</flux:table.cell>
                        <flux:table.cell>{{ $tr->module->hours . ' hours' }}</flux:table.cell>
                        <flux:table.cell align="center">
                            @php
                            $now = now();
                            $start = $tr->module->datestart;
                            $end = $tr->module->dateend;
                            @endphp

                            @if ($now->lt($start))
                            <flux:badge color="amber" size="sm">Pending</flux:badge>
                            @elseif ($now->between($start, $end))
                            <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                            @elseif ($now->gt($end))
                            <flux:badge variant="solid" color="lime" size="sm">Completed</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end" class="flex items-center align-end justify-end gap-2">
                            @if ($now->gt($end))
                            @php $doc = $tr->module->documents->first(); @endphp
                            @if ($doc)
                            <flux:icon.check-badge size="sm" color="lime" />
                            <flux:text
                                size="sm"
                                color="lime">
                                Document Created
                            </flux:text>
                            @else
                            <flux:button
                                href="{{ route('user.documents.create', $tr->id) }}"
                                variant="ghost"
                                size="sm">
                                Create Document
                            </flux:button>
                            @endif
                            @else
                            <flux:button
                                href="{{ route('user.documents.show', $tr->id) }}"
                                variant="ghost"
                                size="sm">
                                View Details
                            </flux:button>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                    @empty
                    <flux:table.row>
                    <flux:table.cell colspan="4" class="text-center py-8">
                        <div class="text-neutral-500">
                            No trainings assigned yet
                        </div>
                    </flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

    </div>
</x-layouts::app>