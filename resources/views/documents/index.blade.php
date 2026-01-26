<x-layouts::app.sidebar title="Training Reports">
    <flux:main>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">All Training Reports</h1>
                <a href="{{ route('documents.create') }}" 
                   class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600">
                    Create New Report
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($documents as $document)
                    <div class="bg-white border rounded-xl p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold mb-1">{{ $document->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $document->fullname }}</p>
                                <div class="flex gap-4 text-sm text-gray-500">
                                    <span>📅 {{ $document->date->format('M d, Y') }}</span>
                                    <span>⏱️ {{ $document->hours }} hours</span>
                                    <span>📍 {{ $document->venue }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('documents.show', $document) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View
                                </a>
                                <a href="{{ route('documents.edit', $document) }}" 
                                   class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-gray-500 mb-4">No training reports found.</p>
                        <a href="{{ route('documents.create') }}" 
                           class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600">
                            Create Your First Report
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $documents->links() }}
            </div>
        </div>
    </flux:main>
</x-layouts::app.sidebar>