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
                <div class="flex gap-3 justify-center items-center">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by title, venue, or topics..."
                            class="w-full px-10 py-1  bg-neutral-secondary-medium border border-default-medium text-heading rounded-2xl focus:ring-brand focus:border-brand placeholder:text-body">
                    </div>
                    <button type="submit" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm rounded-2xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>

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

            <div class="relative w-full overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="flex flex-col justify-center items-center gap-2 p-6">
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">This Year</p>
                    <p class="text-3xl font-bold text-heading">{{ \App\Models\Document::where('user_id', auth()->id())->whereYear('datestart', date('Y'))->count() }}</p>
                </div>
            </div>
        </div>

        @if($documents->count() > 0)

        <!-- Documents Table -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col items-end justify-end gap-4 mt-2 me-2">
                <a href="{{ route('documents.create') }}"
                    class="bg-blue-500 text-white px-3 py-2 rounded-xl hover:bg-blue-600 transition-colors inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create New Learning Journal
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Title
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Venue
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Date Start - Date End
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Hours
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Printed / Date
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-transparent dark:bg-grey-900 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($documents as $document)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('documents.show', $document) }}" wire:navigate class="text-sm font-medium text-heading hover:text-blue-500 transition-colors">
                                    {{ $document->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 truncate max-w-xs">
                                    {{ $document->venue }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-heading whitespace-nowrap">
                                    {{ $document->datestart->format('M d, Y') }} - {{ $document->dateend->format('M d, Y') }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-heading">
                                    {{ $document->hours }} hrs
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $document->created_at->diffForHumans() }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 flex items-center gap-1">
                                    @if($document->isPrinted === 1)
                                    <flux:icon.check class="text-green-600" />
                                    @else
                                    <flux:icon.x-mark class="text-red-500" />
                                    @endif
                                    {{ $document->printedAt ? $document->printedAt->format('M d, Y') : 'Not Yet Printed' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('documents.show', $document) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-xs font-medium rounded-lg transition-colors text-black"
                                        wire:navigate>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('documents.edit', $document) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-neutral-200 hover:bg-neutral-300 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-heading text-xs font-medium rounded-lg transition-colors text-black"
                                        wire:navigate>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($documents instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4">
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