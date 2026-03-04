<x-layouts::app :title="__('Monitoring')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- ── Success banner ── --}}
        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm">
            <div class="flex items-center gap-2">
                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        {{-- ── Search + Year filter ── --}}
        <form method="GET" action="{{ route('hr.monitoring.index') }}" class="p-4">
            <div class="flex gap-3 justify-center items-center">
                <flux:select name="year" class="w-32">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                    @endforeach
                </flux:select>

                <div class="flex-1 relative">
                    <flux:input
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by training title, venue, conductor, or employee…"
                        icon:trailing="magnifying-glass"
                        class="w-full rounded-3xl" />
                </div>

                <flux:button type="submit" variant="primary" icon="magnifying-glass" color="lime" square />

                @if(request('search') || request('year'))
                    <flux:button :href="route('hr.monitoring.index')" variant="ghost">Clear</flux:button>
                @endif
            </div>
        </form>

        {{-- ── Quarter panels ── --}}
        @php
            $quarterColors = [1 => 'teal', 2 => 'lime', 3 => 'sky', 4 => 'violet'];
            $statusConfig  = [
                'upcoming'  => ['color' => 'zinc',   'label' => 'Upcoming'],
                'ongoing'   => ['color' => 'yellow', 'label' => 'Ongoing'],
                'completed' => ['color' => 'green',  'label' => 'Completed'],
            ];
        @endphp

        <div class="flex flex-col gap-4">
            @foreach($quarters as $num => $quarter)
            @php $moduleCount = $quarter['modules']->count(); @endphp

            <div x-data="{ open: {{ $moduleCount > 0 ? 'true' : 'false' }} }"
                 class="rounded-xl border border-white/10 overflow-hidden">

                {{-- Quarter header --}}
                <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-5 py-4 bg-white/5 hover:bg-white/10 transition-colors text-left">
                    <div class="flex items-center gap-3">
                        <flux:badge color="{{ $quarterColors[$num] }}" size="sm">Q{{ $num }}</flux:badge>
                        <div>
                            <flux:heading size="sm" class="leading-none">{{ $quarter['label'] }}</flux:heading>
                            <flux:text size="xs" class="text-zinc-400 mt-0.5">{{ $quarter['range'] }}</flux:text>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <flux:badge color="{{ $moduleCount > 0 ? $quarterColors[$num] : 'zinc' }}" size="sm">
                            {{ $moduleCount }} {{ Str::plural('training', $moduleCount) }}
                        </flux:badge>
                        <svg class="w-4 h-4 text-zinc-400 transition-transform duration-200"
                             :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>

                {{-- Quarter body --}}
                <div x-show="open" x-collapse>
                    @if($moduleCount === 0)
                        <div class="px-5 py-8 text-center">
                            <flux:text class="text-zinc-500">No trainings in {{ $quarter['label'] }} {{ $year }}.</flux:text>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <flux:table>
                                <flux:table.columns>
                                    <flux:table.column>#</flux:table.column>
                                    <flux:table.column>Training Title</flux:table.column>
                                    <flux:table.column>Venue</flux:table.column>
                                    <flux:table.column>Date Start – Date End</flux:table.column>
                                    <flux:table.column>Hours</flux:table.column>
                                    <flux:table.column>Conducted By</flux:table.column>
                                    <flux:table.column align="center">Assigned</flux:table.column>
                                    <flux:table.column align="center">Status</flux:table.column>
                                </flux:table.columns>

                                <flux:table.rows>
                                    @foreach($quarter['modules'] as $mi => $module)
                                    @php
                                        $assignmentCount = $module->assignments->count();
                                        $documentCount   = $module->documents->count();
                                        $isCompleted     = $module->status === 'completed';
                                        $sc              = $statusConfig[$module->status];

                                        // Progress bar width — Tailwind fraction classes only, no inline styles
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



                                        {{-- Module row --}}
                                        <flux:table.row x-data="{ expanded: false }"
                                            :key="'mod-'.$module->id"
                                            class="{{ $isCompleted ? 'cursor-pointer' : '' }}"
                                            x-on:click="{{ $isCompleted ? 'expanded = !expanded' : '' }}">

                                            <flux:table.cell>
                                                <span class="text-sm text-zinc-500">{{ $mi + 1 }}</span>
                                            </flux:table.cell>

                                            <flux:table.cell>
                                                <div class="flex items-center gap-2">
                                                    @if($isCompleted)
                                                        <svg class="w-3.5 h-3.5 text-zinc-400 shrink-0 transition-transform duration-200"
                                                             :class="expanded ? 'rotate-90' : ''"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    @endif
                                                    <span class="text-sm font-medium">{{ $module->title }}</span>
                                                </div>
                                                @if($isCompleted)
                                                    <flux:text size="xs" class="text-zinc-500 mt-0.5 ml-5">
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
                                                <flux:badge color="{{ $quarterColors[$num] }}" size="sm">
                                                    {{ $assignmentCount }}
                                                </flux:badge>
                                            </flux:table.cell>

                                            <flux:table.cell align="center">
                                                <flux:badge color="{{ $sc['color'] }}" size="sm">
                                                    {{ $sc['label'] }}
                                                </flux:badge>
                                            </flux:table.cell>

                                        </flux:table.row>

                                        {{-- Inner expandable row — completed modules only --}}
                                        @if($isCompleted)
                                        <flux:table.row :key="'docs-'.$module->id" x-show="expanded" x-collapse>
                                            <flux:table.cell class="p-0 border-t border-white/10" colspan="8">
                                                <div class="px-6 py-4">

                                                    <flux:heading size="xs" class="mb-3 text-zinc-400 uppercase tracking-widest">
                                                        Document Submissions — {{ $module->title }}
                                                    </flux:heading>

                                                    <flux:table>
                                                        <flux:table.columns>
                                                            <flux:table.column>#</flux:table.column>
                                                            <flux:table.column>Employee</flux:table.column>
                                                            <flux:table.column>Position</flux:table.column>
                                                            <flux:table.column align="center">Document</flux:table.column>
                                                            <flux:table.column align="center">Printed</flux:table.column>
                                                            <flux:table.column>Date Submitted</flux:table.column>
                                                            <flux:table.column>Printed On</flux:table.column>
                                                        </flux:table.columns>

                                                        <flux:table.rows>
                                                            @forelse($module->assignments as $ai => $assignment)
                                                            @php
                                                                $doc     = $module->documentsByUser->get($assignment->user_id);
                                                                $hasDoc  = $doc !== null;
                                                                $printed = $hasDoc && $doc->isPrinted === 1;
                                                            @endphp
                                                            <flux:table.row :key="'asgn-'.$assignment->id">

                                                                <flux:table.cell>
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

                                                            </flux:table.row>
                                                            @empty
                                                            <flux:table.row>
                                                                <flux:table.cell colspan="7" class="text-center py-6">
                                                                    <flux:text class="text-zinc-500">No users assigned to this training.</flux:text>
                                                                </flux:table.cell>
                                                            </flux:table.row>
                                                            @endforelse
                                                        </flux:table.rows>
                                                    </flux:table>

                                                    {{-- Submission summary --}}
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
                                            </flux:table.cell>
                                        </flux:table.row>
                                        @endif


                                    @endforeach
                                </flux:table.rows>
                            </flux:table>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    </div>
</x-layouts::app>