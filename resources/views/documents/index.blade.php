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
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <form method="GET" action="{{ route('documents.index') }}" class="p-4">
                <div class="flex gap-3">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by title, venue, or topics..."
                            class="w-full pl-10 bg-neutral-secondary-medium border border-default-medium text-heading rounded-xl focus:ring-brand focus:border-brand placeholder:text-body">
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-xl transition-colors">
                        Search
                    </button>
                    @if(request('search'))
                    <a href="{{ route('documents.index') }}" class="px-6 py-2.5 bg-neutral-200 hover:bg-neutral-300 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-heading font-medium rounded-xl transition-colors">
                        Clear
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="flex flex-col justify-center items-center gap-2 p-6">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Journals</p>
                    <p class="text-3xl font-bold text-heading">{{ \App\Models\Document::where('user_id', auth()->id())->count() }}</p>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="flex flex-col justify-center items-center gap-2 p-6">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Hours</p>
                    <p class="text-3xl font-bold text-heading">{{ \App\Models\Document::where('user_id', auth()->id())->sum('hours') }}</p>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="flex flex-col justify-center items-center gap-2 p-6">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">This Year</p>
                    <p class="text-3xl font-bold text-heading">{{ \App\Models\Document::where('user_id', auth()->id())->whereYear('date', date('Y'))->count() }}</p>
                </div>
            </div>
        </div>

        @if($documents->count() > 0)

        <!-- Documents Grid -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($documents as $document)
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex grow w-max justify-items-stretch gap-4 items-center">
                        <!-- Title -->
                        <div class="w-full">
                            <a href="{{ route('documents.show', $document) }}" wire:navigate>
                                <h3 class="text-lg font-semibold text-heading hover:text-blue-500 transition-colors line-clamp-2">
                                    {{ $document->title }}
                                </h3>
                            </a>
                        </div>


                        <div class="w-full">
                            <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Date</label>
                            <p class="text-sm text-heading">{{ $document->date->format('M d, Y') }}</p>
                        </div>

                        <div class="col-span-2 w-full">
                            <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Venue</label>
                            <p class="text-sm text-heading truncate">{{ $document->venue }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2 mt-4 w-full">
                            <a href="{{ route('documents.show', $document) }}"
                                class="flex-1 text-center bg-blue-500 hover:bg-blue-600 text-sm font-sm px-4 py-2 rounded-xl transition-colors"
                                wire:navigate>
                                View
                            </a>
                            <a href="{{ route('documents.edit', $document) }}"
                                class="flex-1 text-center dark:bg-neutral-700 dark:hover:bg-neutral-600 text-sm font-sm px-4 py-2 rounded-xl transition-colors"
                                wire:navigate>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-3 bg-neutral-50 dark:bg-neutral-800 border-t border-neutral-200 dark:border-neutral-700">
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        Created {{ $document->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if(is_a($documents, 'Illuminate\Pagination\LengthAwarePaginator'))
        <div class="mt-6">
            {{ $documents->links() }}
        </div>
        @endif

        @else

        <!-- Empty State -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col items-center justify-center p-16 text-center">
                <div class="bg-neutral-100 dark:bg-neutral-800 w-20 h-20 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-heading mb-3">No Learning Journals Yet</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-6">Create your first journal to get started</p>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-medium px-8 py-3 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create First Journal
                </a>
            </div>
        </div>

        @endif
    </div>
</x-layouts::app>
