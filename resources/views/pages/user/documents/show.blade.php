<x-layouts::app :title="$document->title">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-8">
        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm mb-4">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm mb-4">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('user.documents.index') }}" class="inline-flex items-center gap-2 text-blue-500 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to All Documents
            </a>
        </div>

        <!-- Header with Actions -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-heading mb-2">Title of L&D program attended: {{ $document->title }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">Created {{ $document->created_at->diffForHumans() }}</p>
            </div>

            <p class="text-sm text-neutral-600 dark:text-neutral-400 flex items-center gap-1">
                Print Status:
                @if($document->isPrinted === 1)
                <flux:icon.check class="text-green-600" />
                @else
                <flux:icon.x-mark class="text-red-500" />
                @endif
                {{ $document->printedAt ? $document->printedAt->format('M d, Y') : 'Not Yet Printed' }}
            </p>
            <div class="flex gap-3">

                <flux:button
                    icon:trailing="document-text"
                    :href="route('user.documents.preview', $document)"
                    variant="primary"
                    color="sky">Export Word
                </flux:button>

                <flux:modal.trigger name="delete-document">
                    <flux:button
                        icon="trash"
                        variant="primary"
                        color="red"
                        type="button">
                        Delete
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Document Content -->
        <div class="relative overflow-hidden">
            <div class="p-6 space-y-6">
                <!-- Training Information Grid -->
                <flux:separator text="Personal Information Section" />
                <div class="pb-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Employee Name</label>
                        <p class="text-base text-heading">{{ $document->fullname }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Hours</label>
                        <p class="text-base text-heading">{{ $document->hours }} hours</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Date Started</label>
                        <p class="text-base text-heading">{{ $document->datestart->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Date Ended</label>
                        <p class="text-base text-heading">{{ $document->dateend->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Venue</label>
                        <p class="text-base text-heading">{{ $document->venue }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-1">Conducted By</label>
                        <p class="text-base text-heading">{{ $document->conductedby }}</p>
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

                <flux:separator text="Learning Section" />
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

        <!-- Print Preview Modal -->
        <div id="printModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50">
            <div class="flex w-full h-full items-center justify-center p-4">
                <div class="bg-white w-full max-w-4xl h-[90vh] rounded-2xl shadow-2xl overflow-hidden border border-gray-200">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 bg-linear-to-r from-gray-50 to-white border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-gray-700" viewBox="0 0 16 16">
                                <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1" />
                                <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-800">Print Preview</h2>
                        </div>

                        <button
                            class="flex items-center justify-center w-9 h-9 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                            onclick="closePrintPreview()"
                            title="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Iframe Container -->
                    <div class="relative w-full bg-gray-50" style="height: calc(100% - 73px);">
                        <iframe
                            src="{{ route('user.documents.preview', $document) }}"
                            class="w-full h-full border-0"
                            title="Document preview">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal using Flux -->
    <flux:modal name="delete-document" class="max-w-md">
        <form action="{{ route('user.documents.destroy', $document->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <!-- Modal Header with Icon -->
            <div class="p-6 bg-white dark:bg-neutral-800">
                <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                    <flux:icon.exclamation-triangle class="w-8 h-8 text-red-500" />
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
                <flux:heading size="lg" class="text-center text-zinc-900 dark:text-white">
                    Delete Document?
                </flux:heading>

                <div class="rounded-lg p-4 shadow-sm">
                    <flux:text size="sm" class="text-zinc-900 dark:text-white text-center">
                        You are about to delete:
                    </flux:text>
                    <flux:text size="lg" class="font-semibold text-zinc-900 dark:text-white text-center mt-2">
                        {{ $document->title }}
                    </flux:text>
                </div>

                <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4 shadow-sm">
                    <div class="flex flex-col items-center gap-2">
                        <flux:icon.information-circle class="w-5 h-5 text-red-500 dark:text-red-400" />
                        <flux:text size="sm" class="text-zinc-900 dark:text-white text-center">
                            <strong class="font-semibold text-red-500">Warning:</strong> This action cannot be undone. All associated data will be permanently deleted.
                        </flux:text>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm" class="flex-1">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    variant="primary"
                    color="red"
                    size="sm"
                    class="flex-1">
                    Delete Permanently
                </flux:button>
            </div>
        </form>
    </flux:modal>
</x-layouts::app>
