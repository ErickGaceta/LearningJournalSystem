@props(['modules', 'quarterColor', 'paginator'])

@php
$statusConfig = [
    'upcoming'  => ['color' => 'zinc',   'label' => 'Upcoming'],
    'pending'   => ['color' => 'yellow', 'label' => 'Pending'],
    'ongoing'   => ['color' => 'blue',   'label' => 'Ongoing'],
    'completed' => ['color' => 'green',  'label' => 'Completed'],
    'cancelled' => ['color' => 'red',    'label' => 'Cancelled'],
];
@endphp




<div class="overflow-x-auto">
    <flux:table x-data="{ expanded: { {{ (int) request('expanded_module', 0) }}: {{ request('expanded_module') ? 'true' : 'false' }} } }">
        <flux:table.columns>
            <flux:table.column align="end">#</flux:table.column>
            <flux:table.column>Training Title</flux:table.column>
            <flux:table.column>Venue</flux:table.column>
            <flux:table.column>Date Start - Date End</flux:table.column>
            <flux:table.column>Hours</flux:table.column>
            <flux:table.column>Conducted By</flux:table.column>
            <flux:table.column align="center">Assigned</flux:table.column>
            <flux:table.column align="center">Status</flux:table.column>
            <flux:table.column align="center">Notify</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($modules as $mi => $module)
            @php
            $assignmentCount = $module->assignments->count();
            $documentCount = $module->documents->count();
            $isCompleted = $module->status === 'completed';
            $sc = $statusConfig[$module->status];

            $pct = $assignmentCount > 0
            ? round(($documentCount / $assignmentCount) * 100)
            : 0;
            $barWidth = match(true) {
            $pct >= 100 => 'w-full',
            $pct >= 92  => 'w-11/12',
            $pct >= 83  => 'w-5/6',
            $pct >= 75  => 'w-3/4',
            $pct >= 67  => 'w-2/3',
            $pct >= 58  => 'w-7/12',
            $pct >= 50  => 'w-1/2',
            $pct >= 42  => 'w-5/12',
            $pct >= 33  => 'w-1/3',
            $pct >= 25  => 'w-1/4',
            $pct >= 17  => 'w-1/6',
            $pct >= 8   => 'w-1/12',
            default     => 'w-0',
            };
            @endphp

            <flux:table.row
                :key="'mod-'.$module->id"
                class="{{ $isCompleted ? 'cursor-pointer' : '' }}"
                x-on:click="{{ $isCompleted ? 'expanded['.$module->id.'] ? expanded = {} : expanded = { '.$module->id.': true }' : '' }}">

                <flux:table.cell align="end">
                    <span class="text-sm text-zinc-500">{{ $paginator->firstItem() + $mi }}</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm font-medium">{{ $module->title }}</span>
                    @if($isCompleted)
                    <flux:text size="xs" class="text-zinc-500 mt-0.5">
                        {{ $documentCount }} / {{ $assignmentCount }} submitted
                    </flux:text>
                    @endif
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm truncate max-w-xs block">{{ $module->venue }}</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm whitespace-nowrap">
                        {{ $module->datestart->format('M d, Y') }} – {{ $module->dateend->format('M d, Y') }}
                    </span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm">{{ $module->hours }} hrs</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm">{{ $module->conductedby }}</span>
                </flux:table.cell>

                <flux:table.cell align="center">
                    <flux:badge color="{{ $quarterColor }}" size="sm">{{ $assignmentCount }}</flux:badge>
                </flux:table.cell>

                <flux:table.cell align="center">
                    <flux:badge color="{{ $sc['color'] }}" size="sm">{{ $sc['label'] }}</flux:badge>
                </flux:table.cell>

                <flux:table.cell align="center">
                    @if($isCompleted)
                    <form action="{{ route('hr.modules.notify', $module) }}" method="POST"
                        x-data="{ sent: false }"
                        @submit.prevent="sent = true; $el.submit();">
                        @csrf
                        <flux:button type="submit" size="sm" icon="envelope" variant="ghost"
                            x-bind:disabled="sent" x-tooltip="'Notify unsubmitted users'">
                        </flux:button>
                    </form>
                    @endif
                </flux:table.cell>

                <flux:table.cell align="start">
                    @if($isCompleted)
                    <svg class="w-3 h-3 text-zinc-900 dark:text-zinc-300 mx-auto border border-zinc-300 dark:border-zinc-600 rounded-full transition-transform duration-300"
                        style="transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1)"
                        :style="expanded[{{ $module->id }}] ? 'transform: rotate(90deg)' : 'transform: rotate(0deg)'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    @endif
                </flux:table.cell>
            </flux:table.row>

            @if($isCompleted)
            <flux:table.row :key="'docs-'.$module->id">
                <td colspan="10">
                    <div x-show="expanded[{{ $module->id }}]" x-collapse.duration.300ms>
                        <x-hr.monitoring.submissions-table
                            :module="$module"
                            :assignment-count="$assignmentCount"
                            :document-count="$documentCount"
                            :bar-width="$barWidth"
                            :pct="$pct" />
                    </div>
                </td>
            </flux:table.row>
            @endif

            @endforeach
        </flux:table.rows>
    </flux:table>

</div>
