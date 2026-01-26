<x-layouts::app.sidebar title="Create Training Report">
    <flux:main>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Create New Training Report</h1>

            <form action="{{ route('documents.store') }}" method="POST" class="bg-white border rounded-xl p-6 space-y-6">
                @csrf

                {{-- Basic Information --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="fullname" class="block mb-2 text-base font-medium text-heading">Full Name</label>
                        <input type="text" 
                               name="fullname" 
                               id="fullname" 
                               value="{{ old('fullname', auth()->user()->first_name . ' ' . auth()->user()->last_name) }}"
                               class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                               required>
                        @error('fullname')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="block mb-2 text-base font-medium text-heading">Training Title</label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title') }}"
                               class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                               placeholder="e.g., Advanced Laravel Development"
                               required>
                        @error('title')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="date" class="block mb-2 text-base font-medium text-heading">Date</label>
                        <input type="date" 
                               name="date" 
                               id="date" 
                               value="{{ old('date') }}"
                               class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                               required>
                        @error('date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="hours" class="block mb-2 text-base font-medium text-heading">Training Hours</label>
                        <input type="number" 
                               name="hours" 
                               id="hours" 
                               min="1"
                               value="{{ old('hours') }}"
                               class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                               placeholder="8"
                               required>
                        @error('hours')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="venue" class="block mb-2 text-base font-medium text-heading">Venue</label>
                        <input type="text" 
                               name="venue" 
                               id="venue" 
                               value="{{ old('venue') }}"
                               class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                               placeholder="e.g., Manila Hotel"
                               required>
                        @error('venue')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="registration_fee" class="block mb-2 text-base font-medium text-heading">Registration Fee (₱)</label>
                        <input type="text" 
                               name="registration_fee" 
                               id="registration_fee" 
                               value="{{ old('registration_fee') }}"
                               class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                               placeholder="5000.00"
                               required>
                        @error('registration_fee')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="travel_expenses" class="block mb-2 text-base font-medium text-heading">Travel Expenses (₱)</label>
                        <input type="text" 
                               name="travel_expenses" 
                               id="travel_expenses" 
                               value="{{ old('travel_expenses') }}"
                               class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                               placeholder="2000.00"
                               required>
                        @error('travel_expenses')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Long Form Fields --}}
                <div>
                    <label for="topics" class="block mb-2 text-base font-medium text-heading">Topics Covered</label>
                    <textarea name="topics" 
                              id="topics" 
                              rows="5"
                              class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                              placeholder="List the main topics covered during the training..."
                              required>{{ old('topics') }}</textarea>
                    @error('topics')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="insights" class="block mb-2 text-base font-medium text-heading">Key Insights and Learnings</label>
                    <textarea name="insights" 
                              id="insights" 
                              rows="5"
                              class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                              placeholder="What were the most valuable insights you gained?..."
                              required>{{ old('insights') }}</textarea>
                    @error('insights')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="application" class="block mb-2 text-base font-medium text-heading">Practical Application</label>
                    <textarea name="application" 
                              id="application" 
                              rows="5"
                              class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                              placeholder="How will you apply these learnings in your work?..."
                              required>{{ old('application') }}</textarea>
                    @error('application')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="challenges" class="block mb-2 text-base font-medium text-heading">Challenges Encountered</label>
                    <textarea name="challenges" 
                              id="challenges" 
                              rows="5"
                              class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                              placeholder="What challenges did you face during or after the training?..."
                              required>{{ old('challenges') }}</textarea>
                    @error('challenges')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="appreciation" class="block mb-2 text-base font-medium text-heading">Appreciation and Recommendations</label>
                    <textarea name="appreciation" 
                              id="appreciation" 
                              rows="5"
                              class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs" 
                              placeholder="Share your appreciation and recommendations..."
                              required>{{ old('appreciation') }}</textarea>
                    @error('appreciation')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-xl hover:bg-blue-600">
                        Create Report
                    </button>
                    <a href="{{ route('documents.index') }}" 
                       class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </flux:main>
</x-layouts::app.sidebar>