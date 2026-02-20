<x-layouts::app :title="__('My Trainings')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="space-y-1">
            <flux:heading size="xl">{{ $user->first_name }}'s Training Viewer</flux:heading>
            <flux:subheading>See assigned trainings to you</flux:subheading>
        </div>

        {{-- Desktop --}}
        <div class="hidden lg:block">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Title</flux:table.column>
                    <flux:table.column>Duration</flux:table.column>
                    <flux:table.column align="center">Status</flux:table.column>
                    <flux:table.column align="end">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($trainings as $tr)
                    @php
                        $now = now();
                        $start = $tr->module->datestart;
                        $end = $tr->module->dateend;
                        $doc = $tr->module->documents->first();
                        $isCompleted = $now->gt($end);
                    @endphp
                    <flux:table.row>
                        <flux:table.cell>{{ $tr->module->title }}</flux:table.cell>
                        <flux:table.cell>{{ $tr->module->hours }} hours</flux:table.cell>
                        <flux:table.cell align="center">
                            @if ($now->lt($start))
                                <flux:badge color="amber" size="sm">Pending</flux:badge>
                            @elseif ($now->between($start, $end))
                                <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                            @else
                                <flux:badge color="zinc" size="sm">Completed</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                @if($isCompleted)
                                    @if($doc)
                                        <flux:icon.check-badge size="sm" color="lime" />
                                        <flux:text size="sm" class="text-lime-600">Document Created</flux:text>
                                    @else
                                        <flux:button href="{{ route('user.documents.create', $tr->id) }}" variant="ghost" size="sm">
                                            Create Document
                                        </flux:button>
                                    @endif
                                @else
                                    <flux:modal.trigger name="training-detail-{{ $tr->id }}">
                                        <flux:button variant="ghost" size="sm">View Details</flux:button>
                                    </flux:modal.trigger>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                    @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center py-8 text-neutral-500">
                            No trainings assigned yet
                        </flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        {{-- Mobile --}}
        <div class="lg:hidden space-y-4">
            @forelse($trainings as $tr)
            @php
                $now = now();
                $start = $tr->module->datestart;
                $end = $tr->module->dateend;
                $doc = $tr->module->documents->first();
                $isCompleted = $now->gt($end);
            @endphp
            <flux:card class="p-4 bg-transparent">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold">{{ $tr->module->title }}</span>
                        @if ($now->lt($start))
                            <flux:badge color="amber" size="sm">Pending</flux:badge>
                        @elseif ($now->between($start, $end))
                            <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                        @else
                            <flux:badge color="zinc" size="sm">Completed</flux:badge>
                        @endif
                    </div>

                    <flux:separator />

                    <div class="text-sm text-neutral-500 flex gap-2">
                        Duration: <flux:text variant="subtle">{{ $tr->module->hours }} hours</flux:text>
                    </div>
                    <div class="text-sm text-neutral-500 flex gap-2">
                        Dates: <flux:text variant="subtle">
                            {{ $start->format('M d, Y') }} — {{ $end->format('M d, Y') }}
                        </flux:text>
                    </div>

                    <div class="flex justify-end mt-2">
                        @if($isCompleted)
                            @if($doc)
                                <div class="flex items-center gap-1 text-sm text-lime-600">
                                    <flux:icon.check-badge size="sm" color="lime" />
                                    Document Created
                                </div>
                            @else
                                <flux:button href="{{ route('user.documents.create', $tr->id) }}" variant="ghost" size="sm">
                                    Create Document
                                </flux:button>
                            @endif
                        @else
                            <flux:modal.trigger name="training-detail-{{ $tr->id }}">
                                <flux:button variant="primary" size="sm">View Details</flux:button>
                            </flux:modal.trigger>
                        @endif
                    </div>
                </div>
            </flux:card>
            @empty
            <div class="text-center py-8 text-neutral-500">No trainings assigned yet</div>
            @endforelse
        </div>

    </div>

    {{-- Detail modals outside the table --}}
    @foreach($trainings as $tr)
        <x-user.training-detail-modal :assignment="$tr" />
    @endforeach

</x-layouts::app>