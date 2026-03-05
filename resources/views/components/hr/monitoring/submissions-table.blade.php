@props(['module', 'assignmentCount', 'documentCount', 'barWidth', 'pct'])

<div class="px-6 py-4">

    <flux:heading size="xs" class="mb-3 text-zinc-400 uppercase tracking-widest">
        Document Submissions — {{ $module->title }}
    </flux:heading>

    <flux:table>
        <flux:table.columns>
            <flux:table.column align="center">#</flux:table.column>
            <flux:table.column>Employee</flux:table.column>
            <flux:table.column>Position</flux:table.column>
            <flux:table.column align="center">Document</flux:table.column>
            <flux:table.column align="center">Printed</flux:table.column>
            <flux:table.column>Date Submitted</flux:table.column>
            <flux:table.column>Printed On</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($module->assignments as $ai => $assignment)
            @php
            $doc = $module->documentsByUser->get($assignment->user_id);
            $hasDoc = $doc !== null;
            $printed = $hasDoc && $doc->isPrinted === 1;
            @endphp
            <flux:table.row :key="'asgn-'.$assignment->id">

                <flux:table.cell align="center">
                    <span class="text-sm text-zinc-500">{{ $ai + 1 }}</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm font-medium">{{ $assignment->employee_name }}</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm text-zinc-400">
                        {{ $assignment->user?->position?->positions ?? '—' }}
                    </span>
                </flux:table.cell>

                <flux:table.cell align="center">
                    @if($hasDoc)
                    <div class="flex items-center justify-center gap-1">
                        <flux:icon.check class="text-green-500 w-4 h-4" />
                        <flux:badge color="green" size="sm">Submitted</flux:badge>
                    </div>
                    @else
                    <div class="flex items-center justify-center gap-1">
                        <flux:icon.x-mark class="text-red-500 w-4 h-4" />
                        <flux:badge color="red" size="sm">No Document</flux:badge>
                    </div>
                    @endif
                </flux:table.cell>

                <flux:table.cell align="center">
                    @if(!$hasDoc)
                    <span class="text-zinc-600 text-sm">—</span>
                    @elseif($printed)
                    <div class="flex items-center justify-center gap-1">
                        <flux:icon.check class="text-green-500 w-4 h-4" />
                        <flux:badge color="green" size="sm">Printed</flux:badge>
                    </div>
                    @else
                    <flux:badge color="zinc" size="sm">Pending</flux:badge>
                    @endif
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm">
                        {{ $hasDoc ? $doc->created_at->format('M d, Y') : '—' }}
                    </span>
                </flux:table.cell>

                <flux:table.cell>
                    @if($hasDoc && $printed && $doc->printedAt)
                    <span class="text-sm">{{ $doc->printedAt->format('M d, Y') }}</span>
                    <flux:text size="xs" class="text-zinc-500">
                        ({{ $doc->printCount }} {{ Str::plural('time', $doc->printCount) }})
                    </flux:text>
                    @else
                    <span class="text-sm">—</span>
                    @endif
                </flux:table.cell>

                <flux:table.cell align="center">
                    @if($hasDoc)
                    <flux:dropdown>
                        <flux:button size="sm" variant="ghost" icon:trailing="ellipsis-horizontal"></flux:button>
                        <flux:menu>
                            <flux:menu.item
                                icon="bolt"
                                x-data
                                x-on:click="$dispatch('open-document-preview', { id: {{ $doc->id }} })">
                                Generate Certificate
                            </flux:menu.item>
                            <flux:menu.item
                                icon="eye"
                                x-data
                                x-on:click="$dispatch('open-document-preview', { id: {{ $doc->id }}, toolbar: '0' })">
                                View PDF
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                    @else
                    <span class="text-zinc-600 text-sm">—</span>
                    @endif
                </flux:table.cell>

            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="8" class="text-center py-6">
                    <flux:text class="text-zinc-500">No users assigned to this training.</flux:text>
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    {{-- Submission summary bar --}}
    @if($assignmentCount > 0)
    <div class="mt-3 flex items-center gap-4">
        <flux:text size="xs" class="text-zinc-500">
            <span class="text-green-400 font-medium">{{ $documentCount }}</span>
            / {{ $assignmentCount }} submitted
        </flux:text>
        <flux:text size="xs" class="text-zinc-500">
            <span class="text-green-400 font-medium">
                {{ $module->documents->where('isPrinted', 1)->count() }}
            </span>
            printed
        </flux:text>
        <div class="flex-1 bg-white/10 rounded-full h-1.5 max-w-xs">
            <div class="bg-green-500 h-1.5 rounded-full transition-all {{ $barWidth }}"></div>
        </div>
        <flux:text size="xs" class="text-zinc-500">{{ $pct }}%</flux:text>
    </div>
    @endif

</div>