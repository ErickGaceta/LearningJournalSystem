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
            <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 text-blue-500 hover:text-blue-600 transition-colors">
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
            <div class="flex gap-3">
                <a
                    href="{{ route('documents.export.word', $document) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl inline-flex items-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                        <path d="M4.879 4.515a.5.5 0 0 1 .606.364l1.036 4.144.997-3.655a.5.5 0 0 1 .964 0l.997 3.655 1.036-4.144a.5.5 0 0 1 .97.242l-1.5 6a.5.5 0 0 1-.967.01L8 7.402l-1.018 3.73a.5.5 0 0 1-.967-.01l-1.5-6a.5.5 0 0 1 .364-.606z" />
                    </svg>
                    Export Word
                </a>

                <button
                    type="button"
                    onclick="confirmDelete()"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>

                <form id="deleteForm" action="{{ route('documents.destroy', $document) }}" method="POST" class="hidden">

                    <!-- Delete Form - Hidden -->
                    <form id="deleteForm" action="{{ route('documents.destroy', $document->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
            </div>
        </div>

        <!-- Document Content -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-6 space-y-6">
                <!-- Training Information Grid -->
               <div class="pb-6 border-b border-neutral-200 dark:border-neutral-700 space-y-4">
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
                    <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
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
                            src="{{ route('documents.print.preview', $document) }}"
                            class="w-full h-full border-0"
                            title="Document preview">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPrintPreview() {
            const modal = document.getElementById('printModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closePrintPreview() {
            const modal = document.getElementById('printModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Close print modal on backdrop click
        document.addEventListener('DOMContentLoaded', function() {
            const printModal = document.getElementById('printModal');
            if (printModal) {
                printModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closePrintPreview();
                    }
                });
            }
        });

        function confirmDelete() {
            // Create custom modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                    <div class="bg-white dark:bg-neutral-800 rounded-2xl p-6 max-w-md mx-4 shadow-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Delete Document</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">This action cannot be undone</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            Are you sure you want to delete <strong>"{{ $document->title }}"</strong>?
                            All data associated with this document will be permanently removed.
                        </p>
                        <div class="flex gap-3 justify-end">
                            <button
                                onclick="this.closest('.fixed').remove()"
                                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </button>
                            <button
                                onclick="document.getElementById('deleteForm').submit()"
                                class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                                Delete Document
                            </button>
                        </div>
                    </div>
                `;
            document.body.appendChild(modal);

            // Close on backdrop click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        function confirmDelete() {
            // Create custom confirmation modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]';
            modal.innerHTML = `
                <div class="bg-white dark:bg-neutral-800 rounded-2xl p-6 max-w-md mx-4 shadow-2xl">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Delete Document</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">This action cannot be undone</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Are you sure you want to delete <strong>"{{ $document->title }}"</strong>?
                        All data associated with this document will be permanently removed.
                    </p>
                    <div class="flex gap-3 justify-end">
                        <button
                            onclick="closeDeleteModal()"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button
                            onclick="submitDeleteForm()"
                            class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                            Delete Document
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Store modal reference
            window.deleteModal = modal;

            // Close on backdrop click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeDeleteModal();
                }
            });

            // Close on Escape key
            document.addEventListener('keydown', handleEscapeKey);
        }

        function handleEscapeKey(e) {
            if (e.key === 'Escape' && window.deleteModal) {
                closeDeleteModal();
            }
        }

        function closeDeleteModal() {
            if (window.deleteModal) {
                window.deleteModal.remove();
                window.deleteModal = null;
                document.removeEventListener('keydown', handleEscapeKey);
            }
        }

        function submitDeleteForm() {
            // Get the form
            const form = document.getElementById('deleteForm');

            if (form) {
                // Close the modal
                closeDeleteModal();

                // Submit the form
                form.submit();
            } else {
                console.error('Delete form not found');
                alert('Error: Could not find delete form. Please refresh and try again.');
            }
        }
    </script>
    </div>
</x-layouts::app>
