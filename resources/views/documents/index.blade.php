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

        <!-- Documents Table -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Venue
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($documents as $document)
                        <tr class="hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('documents.show', $document) }}" wire:navigate class="text-sm font-medium text-heading hover:text-blue-500 transition-colors">
                                    {{ $document->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-heading">{{ $document->date->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-heading">{{ $document->venue }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $document->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('documents.show', $document) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-lg transition-colors"
                                    wire:navigate>
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
