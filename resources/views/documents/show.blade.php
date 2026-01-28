<x-layouts::app :title="$document->title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-8">
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm mb-4">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Header with Actions -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-heading mb-2">Title of L&D program attended: {{ $document->title }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">Created {{ $document->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-3">
                <a href="" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl transition-colors">Print</a>
                <a href="{{ route('documents.edit', $document) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl transition-colors">
                    Edit
                </a>
                <form action="{{ route('documents.destroy', $document) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this document?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Document Content -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6 space-y-6">
                <!-- Training Information Grid -->
                <div class="grid grid-cols-1 md:grid-rows-1 gap-6 pb-6 border-b border-neutral-200 dark:border-neutral-700">
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Employee Name</label>
                        <p class="text-base text-heading">{{ $document->fullname }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Hours</label>
                        <p class="text-base text-heading">{{ $document->hours }} hours</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Date</label>
                        <p class="text-base text-heading">{{ $document->date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Venue</label>
                        <p class="text-base text-heading">{{ $document->venue }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Registration Fee</label>
                        <p class="text-base text-heading">₱{{ $document->registration_fee }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Travel Expenses</label>
                        <p class="text-base text-heading">₱{{ $document->travel_expenses }}</p>
                    </div>
                </div>

                <!-- Learning Sections -->
                <div class="space-y-6">
                    <!-- Topics -->
                    <div>
                        <h3 class="text-lg font-semibold text-heading mb-3">A. Topics Covered</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">(Knowledge, skills, attitude, information)</p>
                        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-4">
                            <p class="text-base text-heading whitespace-pre-wrap">{{ $document->topics }}</p>
                        </div>
                    </div>

                    <!-- Insights -->
                    <div>
                        <h3 class="text-lg font-semibold text-heading mb-3">B. Key Insights and Discoveries</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">(Understanding, perception, awareness)</p>
                        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-4">
                            <p class="text-base text-heading whitespace-pre-wrap">{{ $document->insights }}</p>
                        </div>
                    </div>

                    <!-- Application -->
                    <div>
                        <h3 class="text-lg font-semibold text-heading mb-3">C. Practical Application</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">(How I will apply new learnings)</p>
                        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-4">
                            <p class="text-base text-heading whitespace-pre-wrap">{{ $document->application }}</p>
                        </div>
                    </div>

                    <!-- Challenges -->
                    <div>
                        <h3 class="text-lg font-semibold text-heading mb-3">D. Challenges Encountered</h3>
                        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-4">
                            <p class="text-base text-heading whitespace-pre-wrap">{{ $document->challenges }}</p>
                        </div>
                    </div>

                    <!-- Appreciation -->
                    <div>
                        <h3 class="text-lg font-semibold text-heading mb-3">E. Appreciation and Feedback</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">(For management and services of HRD)</p>
                        <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-4">
                            <p class="text-base text-heading whitespace-pre-wrap">{{ $document->appreciation }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 text-blue-500 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to All Documents
            </a>
        </div>
    </div>
</x-layouts::app>
