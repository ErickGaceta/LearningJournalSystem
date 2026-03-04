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
        @endphp

        <div class="flex flex-col gap-4">
            @foreach($quarters as $num => $quarter)
                <x-hr.monitoring.quarter-panel
                    :num="$num"
                    :quarter="$quarter"
                    :color="$quarterColors[$num]"
                    :year="$year" />
            @endforeach
        </div>

    </div>

    {{-- ── Document preview modal ──
         Lives here at the top level so it's always in the DOM,
         regardless of which quarter panels are expanded.
    --}}
    <div
        x-data="{ open: false, docId: null }"
        x-on:open-document-preview.window="open = true; docId = $event.detail.id"
        x-show="open"
        x-cloak
        style="display:none"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
        @keydown.escape.window="open = false">

        <div
            class="relative w-full max-w-4xl bg-zinc-900 rounded-2xl shadow-2xl overflow-hidden border border-white/10"
            @click.outside="open = false">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-white/10 bg-white/5">
                <flux:heading size="sm">Document Preview</flux:heading>
                <flux:button size="sm" variant="ghost" icon="x-mark" x-on:click="open = false; docId = null" />
            </div>

            {{-- Iframe — only mounts when modal is open so it doesn't pre-fetch --}}
            <template x-if="open && docId">
                <iframe
                    :src="`{{ url('hr/monitoring/documents') }}/${docId}/preview`"
                    style="width:100%; height:78vh; border:none; display:block;"
                    loading="lazy"
                    title="Learning Journal Preview">
                </iframe>
            </template>

        </div>
    </div>

</x-layouts::app>