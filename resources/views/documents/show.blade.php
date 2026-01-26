<x-layouts::app.sidebar :title="$document->title">
    <flux:main>
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $document->title }}</h1>
                    <p class="text-gray-600">{{ $document->fullname }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('documents.edit', $document) }}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600">
                        Edit
                    </a>
                    <form action="{{ route('documents.destroy', $document) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this report?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white border rounded-xl p-6 space-y-6">
                {{-- Training Information --}}
                <div class="grid grid-cols-2 gap-4 pb-6 border-b">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Date</label>
                        <p class="text-lg">{{ $document->date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Hours</label>
                        <p class="text-lg">{{ $document->hours }} hours</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Venue</label>
                        <p class="text-lg">{{ $document->venue }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Registration Fee</label>
                        <p class="text-lg">₱{{ $document->registration_fee }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Travel Expenses</label>
                        <p class="text-lg">₱{{ $document->travel_expenses }}</p>
                    </div>
                </div>

                {{-- Topics Covered --}}
                <div>
                    <h3 class="text-xl font-semibold mb-3">Topics Covered</h3>
                    <div class="prose max-w-none">
                        {!! nl2br(e($document->topics)) !!}
                    </div>
                </div>

                {{-- Key Insights --}}
                <div>
                    <h3 class="text-xl font-semibold mb-3">Key Insights and Learnings</h3>
                    <div class="prose max-w-none">
                        {!! nl2br(e($document->insights)) !!}
                    </div>
                </div>

                {{-- Practical Application --}}
                <div>
                    <h3 class="text-xl font-semibold mb-3">Practical Application</h3>
                    <div class="prose max-w-none">
                        {!! nl2br(e($document->application)) !!}
                    </div>
                </div>

                {{-- Challenges --}}
                <div>
                    <h3 class="text-xl font-semibold mb-3">Challenges Encountered</h3>
                    <div class="prose max-w-none">
                        {!! nl2br(e($document->challenges)) !!}
                    </div>
                </div>

                {{-- Appreciation --}}
                <div>
                    <h3 class="text-xl font-semibold mb-3">Appreciation and Recommendations</h3>
                    <div class="prose max-w-none">
                        {!! nl2br(e($document->appreciation)) !!}
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('documents.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← Back to All Reports
                </a>
            </div>
        </div>
    </flux:main>
</x-layouts::app.sidebar>