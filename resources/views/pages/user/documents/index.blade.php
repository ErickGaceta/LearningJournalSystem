<x-layouts::app :title="__('All Documents')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm">
            <div class="flex items-center">
                <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        <!-- Search Bar -->
        <div class="relative overflow-hidden">
            <form method="GET" action="{{ route('user.documents.index') }}" class="p-4">
                <div class="flex gap-3 justify-center items-center">
                    <div class="flex-1 relative">
                        <flux:input
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by title, venue, or topics..."
                            icon:trailing="magnifying-glass"
                            class="w-full rounded-3xl" />
                    </div>
                    <flux:button type="submit" variant="primary" icon="magnifying-glass" color="lime" square />
                    @if(request('search'))
                    <flux:button
                        :href="route('user.documents.index')"
                        variant="ghost">
                        Clear
                    </flux:button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Total Journals</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $documentCount }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Total Hours</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $totalHours }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg" class="flex">Total Journals This Year <flux:text size="sm">({{ $year }})</flux:text>
                </flux:heading>
                {{ $totalYearlyDocument }}
                <flux:text class="mt-2 mb-4">
                </flux:text>
            </flux:card>
        </div>

        @if($documents->count() > 0)

        <!-- Documents Table -->
        <div class="relative overflow-hidden hidden lg:block">
            <div class="overflow-x-auto">
                <flux:table :paginate="$documents">
                    <flux:table.columns sticky>
                        <flux:table.column>Title</flux:table.column>
                        <flux:table.column>Venue</flux:table.column>
                        <flux:table.column>Date Start - Date End</flux:table.column>
                        <flux:table.column>Hours</flux:table.column>
                        <flux:table.column>Created</flux:table.column>
                        <flux:table.column align="center">Prepared / Date</flux:table.column>
                        <flux:table.column>Actions</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach($documents as $document)
                        <flux:table.row :key="$document->id">

                            <flux:table.cell>
                                <a href="{{ route('user.documents.show', $document) }}"
                                    wire:navigate
                                    class="text-sm font-medium hover:underline">
                                    {{ $document->module->title }}
                                </a>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-sm truncate max-w-xs block">
                                    {{ $document->module->venue }}
                                </span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-sm whitespace-nowrap">
                                    {{ $document->module->datestart->format('M d, Y') }}
                                    -
                                    {{ $document->module->dateend->format('M d, Y') }}
                                </span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-sm">
                                    {{ $document->module->hours }} hrs
                                </span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-sm">
                                    {{ $document->created_at->diffForHumans() }}
                                </span>
                            </flux:table.cell>

                            <flux:table.cell align="center">
                                <div class="flex items-center align-center justify-center gap-1 text-sm">
                                    @if($document->isPrinted === 1)
                                    <flux:icon.check class="text-green-600" />
                                    @else
                                    <flux:icon.x-mark class="text-red-500" />
                                    @endif

                                    {{ $document->printedAt
                            ? $document->printedAt->format('M d, Y')
                            : 'Not Yet Printed' }}
                                </div>
                                <flux:text class="text-xs text-center">Print Count: {{ $document->printCount }}</flux:text>
                            </flux:table.cell>

                            <flux:table.cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button
                                        :href="route('user.documents.show', $document)"
                                        variant="ghost"
                                        size="sm"
                                        icon="eye"
                                        wire:navigate />
                                </div>
                            </flux:table.cell>

                        </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>

            </div>
        </div>
 <!-- Mobile View -->
        <div class="lg:hidden space-y-4">
            @foreach($documents as $document)
            <flux:card class="p-4 bg-transparent">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between align-center items-center">
                        <flux:heading>
                            {{ $document->module->title }}
                        </flux:heading>
                        <flux:button
                            :href="route('user.documents.show', $document)"
                            variant="ghost"
                            size="sm"
                            icon="eye"
                            wire:navigate />
                    </div>

                    <flux:separator />

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Venue: <flux:text variant="subtle">{{ $document->module->venue }}</flux:text>
                    </div>

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Dates: <flux:text variant="subtle"> {{ $document->module->datestart->format('M d, Y') }}
                            - {{ $document->module->dateend->format('M d, Y') }}</flux:text>
                    </div>

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Hours: <flux:text variant="subtle"> {{ $document->module->hours }} hrs</flux:text>
                    </div>

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Created: <flux:text variant="subtle"> {{ $document->created_at->diffForHumans() }}</flux:text>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm">
                            @if($document->isPrinted === 1)
                            <flux:icon.check variant="micro" class="text-green-600" />
                            @else
                            <flux:icon.x-mark variant="micro" class="text-red-500" />
                            @endif
                            <flux:text variant="subtle">{{ $document->printedAt ? $document->printedAt->format('M d, Y') : 'Not Yet Printed' }}</flux:text>
                        </div>
                        <flux:text class="text-xs text-right">Print Count: {{ $document->printCount }}</flux:text>
                    </div>
                </div>
            </flux:card>
            @endforeach
        </div>

        @else

        <!-- Empty State -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col items-center justify-center p-16 text-center">
                <div class="bg-neutral-100 dark:bg-neutral-800 w-20 h-20 rounded-full flex items-center justify-center mb-6">
                    <flux:icon.document class="size-10 text-neutral-400" />
                </div>
                <h3 class="text-xl font-semibold text-heading mb-3">No Learning Journals Yet</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-6">Create your first journal to get started</p>
            </div>
        </div>

        @endif
    </div>
</x-layouts::app>
