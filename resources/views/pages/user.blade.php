 <x-layouts::app :title="__('DOST CAR Learning Journal System - User')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        
        <!-- Welcome Section -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-heading">Welcome, {{ auth()->user()->first_name }}!</h1>
            <p class="text-sm text-neutral-600">Manage your learning journal entries</p>
        </div>

        <!-- Create New Document Button -->
        <div class="rounded-xl border border-neutral-200 p-6">
            <div class="flex flex-col items-center justify-center gap-4">
                <p class="text-lg font-medium text-heading">Ready to document your learning?</p>
                <a href="{{ route('documents.create') }}" 
                   class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600 transition-colors inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create New Learning Journal
                </a>
            </div>
        </div>

        <!-- Optional: Display recent documents -->
        <!-- You can add this later -->
    </div>
</x-layouts::app>