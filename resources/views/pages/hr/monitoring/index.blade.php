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
        <div class="relative overflow-hidden">
            <form method="GET" action="{{ route('hr.monitoring.index') }}" class="p-4">
                <div class="flex gap-3 justify-center items-center">

                    {{-- Year picker --}}
                    <flux:select name="year" class="w-32">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                        @endforeach
                    </flux:select>

                    {{-- Search --}}
                    <div class="flex-1 relative">
                        <flux:input
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by employee, module…"
                            icon:trailing="magnifying-glass"
                            class="w-full rounded-3xl" />
                    </div>

                    <flux:button type="submit" variant="primary" icon="magnifying-glass" color="lime" square />

                    @if(request('search') || request('year'))
                    <flux:button :href="route('hr.monitoring.index')" variant="ghost">
                        Clear
                    </flux:button>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Quarter panels ── --}}
        <div class="flex flex-col gap-4">

            @php
                $quarterColors = [
                    1 => 'teal',
                    2 => 'lime',
                    3 => 'sky',
                    4 => 'violet',
                ];
            @endphp

            @foreach($quarters as $num => $quarter)
            @php $count = $quarter['documents']->count(); @endphp

            <div x-data="{ open: {{ $count > 0 ? 'true' : 'false' }} }" class="rounded-xl border border-white/10 overflow-hidden">

                {{-- Quarter header / toggle button --}}
                <button
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-5 py-4 bg-white/5 hover:bg-white/10 transition-colors text-left">

                    <div class="flex items-center gap-3">
                        <flux:badge color="{{ $quarterColors[$num] }}" size="sm">Q{{ $num }}</flux:badge>
                        <div>
                            <flux:heading size="sm" class="leading-none">{{ $quarter['label'] }}</flux:heading>
                            <flux:text size="xs" class="text-zinc-400 mt-0.5">{{ $quarter['range'] }}</flux:text>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <flux:badge
                            color="{{ $count > 0 ? $quarterColors[$num] : 'zinc' }}"
                            size="sm">
                            {{ $count }} {{ Str::plural('document', $count) }}
                        </flux:badge>
                        <svg
                            class="w-4 h-4 text-zinc-400 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>

                {{-- Quarter body --}}
                <div x-show="open" x-collapse>
                    @if($count === 0)
                        <div class="px-5 py-8 text-center">
                            <flux:text class="text-zinc-500">No documents submitted in {{ $quarter['label'] }} {{ $year }}.</flux:text>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <flux:table>
                                <flux:table.columns sticky>
                                    <flux:table.column align="center">#</flux:table.column>
                                    <flux:table.column>Employee</flux:table.column>
                                    <flux:table.column>Training Module</flux:table.column>
                                    <flux:table.column>Venue</flux:table.column>
                                    <flux:table.column>Date Start - Date End</flux:table.column>
                                    <flux:table.column>Hours</flux:table.column>
                                    <flux:table.column>Date Submitted</flux:table.column>
                                    <flux:table.column align="center">Printed / Date</flux:table.column>
                                </flux:table.columns>

                                <flux:table.rows>
                                    @foreach($quarter['documents'] as $i => $document)
                                    <flux:table.row :key="$document->id">

                                        {{-- Row number --}}
                                        <flux:table.cell align="center">
                                            <span class="text-sm text-zinc-500">{{ $i + 1 }}</span>
                                        </flux:table.cell>

                                        {{-- Employee --}}
                                        <flux:table.cell>
                                            <span class="text-sm font-medium">{{ $document->fullname }}</span>
                                            @if($document->user?->position)
                                                <flux:text size="xs" class="text-zinc-400">
                                                    {{ $document->user->position->positions ?? '—' }}
                                                </flux:text>
                                            @endif
                                        </flux:table.cell>

                                        {{-- Module title --}}
                                        <flux:table.cell>
                                            <span class="text-sm">
                                                {{ $document->module?->title ?? '—' }}
                                            </span>
                                        </flux:table.cell>

                                        {{-- Venue --}}
                                        <flux:table.cell>
                                            <span class="text-sm truncate max-w-xs block">
                                                {{ $document->module?->venue ?? '—' }}
                                            </span>
                                        </flux:table.cell>

                                        {{-- Date range --}}
                                        <flux:table.cell>
                                            <span class="text-sm whitespace-nowrap">
                                                @if($document->module?->datestart && $document->module?->dateend)
                                                    {{ $document->module->datestart->format('M d, Y') }}
                                                    –
                                                    {{ $document->module->dateend->format('M d, Y') }}
                                                @else
                                                    —
                                                @endif
                                            </span>
                                        </flux:table.cell>

                                        {{-- Hours --}}
                                        <flux:table.cell>
                                            <span class="text-sm">
                                                {{ $document->module?->hours ? $document->module->hours . ' hrs' : '—' }}
                                            </span>
                                        </flux:table.cell>

                                        {{-- Date submitted --}}
                                        <flux:table.cell>
                                            <span class="text-sm whitespace-nowrap">
                                                {{ $document->created_at->format('M d, Y') }}
                                            </span>
                                        </flux:table.cell>

                                        {{-- Printed status --}}
                                        <flux:table.cell align="center">
                                            <div class="flex items-center justify-center gap-1 text-sm">
                                                @if($document->isPrinted === 1)
                                                    <flux:icon.check class="text-green-600" />
                                                @else
                                                    <flux:icon.x-mark class="text-red-500" />
                                                @endif
                                                {{ $document->printedAt ? $document->printedAt->format('M d, Y') : 'Not Yet Printed' }}
                                            </div>
                                            <flux:text class="text-xs text-center">
                                                Print Count: {{ $document->printCount ?? 0 }}
                                            </flux:text>
                                        </flux:table.cell>

                                    </flux:table.row>
                                    @endforeach
                                </flux:table.rows>
                            </flux:table>
                        </div>

                        {{-- Quarter summary footer --}}
                        <div class="px-5 py-3 border-t border-white/10 bg-white/2 flex justify-between items-center">
                            <flux:text size="xs" class="text-zinc-500">
                                {{ $count }} {{ Str::plural('document', $count) }} in {{ $quarter['label'] }} {{ $year }}
                            </flux:text>
                            <flux:text size="xs" class="text-zinc-500">
                                {{ $quarter['documents']->where('isPrinted', true)->count() }} printed
                                · {{ $quarter['documents']->where('isPrinted', false)->count() }} pending
                            </flux:text>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach

        </div>

    </div>
</x-layouts::app>