<x-layouts::app :title="__('DOST CAR Learning Journal System')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Main Form - wrapping all inputs -->
        <form action="{{ route('documents.store') }}" method="POST">
            @csrf

            <!-- Personal Information -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3 mb-4">
                <!-- Personal Information Card -->
                <div class="relative overflow-hidden rounded-xl border border-neutral-200 p-4">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col justify-center items-center gap-2">
                            <p class="text-base font-medium text-neutral-600 dark:text-neutral-400">Personal Information</p>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="employee_id" class="block mb-2.5 text-base font-medium text-heading">Employee ID</label>
                                <input type="number"
                                    id="employee_id"
                                    name="employee_id"
                                    class="text-heading w-full text-sm mt-1 rounded-xl block px-3 py-2 shadow-lg"
                                    value="{{ auth()->user()->employee_id }}"
                                    readonly />
                            </div>
                            <div>
                                <label for="fullname" class="block mb-2.5 text-base font-medium text-heading">Name</label>
                                <input type="text"
                                    id="fullname"
                                    name="fullname"
                                    class="border-none text-heading w-full text-sm mt-1 rounded-xl block px-3 py-2 shadow-lg bg-transparent"
                                    value="{{ old('fullname', auth()->user()->first_name . ' ' . (auth()->user()->middle_name ? auth()->user()->middle_name . ' ' : '') . auth()->user()->last_name) }}"
                                    readonly />
                                @error('fullname')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="flex flex-col">
        <label for="division_units" class="block mb-2.5 text-base font-medium text-heading">Department/Unit/Office</label>
        <input type="text"
            id="division_units"
            name="division_units"
            class="border-none text-heading w-full text-sm rounded-xl px-3 py-2 shadow-lg bg-transparent"
            value="{{ old('division_units', auth()->user()->divisionUnit->division_units ?? 'Not Assigned') }}"
            readonly />
        @error('division_units')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col">
        <label for="positions" class="block mb-2.5 text-base font-medium text-heading">Position</label>
        <input type="text"
            id="positions"
            name="positions"
            class="border-none text-heading w-full text-sm rounded-xl px-3 py-2 shadow-lg bg-transparent"
            value="{{ old('positions', auth()->user()->position->positions ?? 'Not Assigned') }}"
            readonly />
        @error('positions')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
                        </div>
                    </div>
                </div>

                <!-- L&D Program Information Card -->
                <div class="relative overflow-hidden rounded-xl border border-neutral-200 p-4">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col justify-center items-center gap-2">
                            <p class="text-base font-medium text-neutral-600 dark:text-neutral-400">L&D Program Information</p>
                        </div>
                        <div class="grid gap-3">
                            <div>
                                <label for="title" class="block mb-1 text-base font-medium text-heading">L&D Title</label>
                                <input type="text"
                                    id="title"
                                    name="title"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm mt-1 rounded-xl focus:ring-brand focus:border-brand block px-3 py-1 shadow-xs placeholder:text-body"
                                    placeholder="L&D Title"
                                    value="{{ old('title') }}"
                                    required />
                                @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="hours" class="block mb-1 text-base font-medium text-heading">Number of L&D Hours</label>
                                <input type="number"
                                    id="hours"
                                    name="hours"
                                    min="1"
                                    class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-1 shadow-xs placeholder:text-body"
                                    placeholder="L&D Hours"
                                    value="{{ old('hours') }}"
                                    required />
                                @error('hours')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="flex flex-col">
        <label for="datestart" class="block mb-2.5 text-base font-medium text-heading">Date Started</label>
        <input type="date"
            id="datestart"
            name="datestart"
            class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand px-3 py-2 shadow-xs placeholder:text-body"
            placeholder="Select date"
            value="{{ old('datestart') }}"
            required />
        @error('datestart')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col">
        <label for="dateend" class="block mb-2.5 text-base font-medium text-heading">Date Ended</label>
        <input type="date"
            id="dateend"
            name="dateend"
            class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand px-3 py-2 shadow-xs placeholder:text-body"
            placeholder="Select date"
            value="{{ old('dateend') }}"
            required />
        @error('dateend')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
                        </div>
                    </div>
                </div>

                <!-- L&D Additional Information Card -->
                <div class="relative overflow-hidden rounded-xl border border-neutral-200 p-4">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col justify-center items-center gap-2">
                            <p class="text-base font-medium text-neutral-600 dark:text-neutral-400">L&D Additional Information</p>
                        </div>
                        <div class="grid gap-3">
                            <div>
                                <label for="venue" class="block mb-1 text-base font-medium text-heading">Venue</label>
                                <input type="text"
                                    id="venue"
                                    name="venue"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm mt-1 rounded-xl focus:ring-brand focus:border-brand block px-3 py-1 shadow-xs placeholder:text-body"
                                    placeholder="Venue"
                                    value="{{ old('venue') }}"
                                    required />
                                @error('venue')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="conductedby" class="block mb-1 text-base font-medium text-heading">Conducted/ Sponsored By</label>
                                <input type="text"
                                    id="conductedby"
                                    name="conductedby"
                                    class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm mt-1 rounded-xl focus:ring-brand focus:border-brand block px-3 py-1 shadow-xs placeholder:text-body"
                                    placeholder="Conducted By"
                                    value="{{ old('conductedby') }}"
                                    required />
                                @error('conductedby')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="flex flex-col">
        <label for="registration_fee" class="block mb-2.5 text-base font-medium text-heading">Registration Fee</label>
        <input type="text"
            id="registration_fee"
            name="registration_fee"
            class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand px-3 py-2 shadow-xs placeholder:text-body"
            placeholder="Registration Fee"
            value="{{ old('registration_fee') }}"
            required />
        @error('registration_fee')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col">
        <label for="travel_expenses" class="block mb-2.5 text-base font-medium text-heading">Travel Expenses</label>
        <input type="text"
            id="travel_expenses"
            name="travel_expenses"
            class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand px-3 py-2 shadow-xs placeholder:text-body"
            placeholder="Travel Expenses"
            value="{{ old('travel_expenses') }}"
            required />
        @error('travel_expenses')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area with Form -->
            <div class="relative h-fit flex-1 overflow-hidden rounded-xl border border-neutral-200 mb-4">
                <div class="w-full p-4">
                    <div class="mb-4">
                        <label for="topics" class="block mb-1 text-base font-medium text-heading">A. I learned the following from the L&D program I attended...</label>
                        <p class="text-xs text-neutral-600 mb-2">(Knowledge, skills, attitude, information.) Please indicate the topic/topics</p>
                        <textarea required
                            name="topics"
                            id="topics"
                            rows="4"
                            class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body"
                            placeholder="Enter text here">{{ old('topics') }}</textarea>
                        @error('topics')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="insights" class="block mb-1 text-base font-medium text-heading">B. I gained the following insights and discoveries...</label>
                        <p class="text-xs text-neutral-600 mb-2">(Understanding, perception, awareness)</p>
                        <textarea required
                            name="insights"
                            id="insights"
                            rows="4"
                            class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body"
                            placeholder="Enter text here">{{ old('insights') }}</textarea>
                        @error('insights')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="application" class="block mb-1 text-base font-medium text-heading">C. I will apply the new learnings in my current function by doing the following...</label>
                        <textarea required
                            name="application"
                            id="application"
                            rows="4"
                            class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body"
                            placeholder="Enter text here">{{ old('application') }}</textarea>
                        @error('application')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="challenges" class="block mb-1 text-base font-medium text-heading">D. I was challenged most on...</label>
                        <textarea required
                            name="challenges"
                            id="challenges"
                            rows="4"
                            class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body"
                            placeholder="Enter text here">{{ old('challenges') }}</textarea>
                        @error('challenges')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="appreciation" class="block mb-1 text-base font-medium text-heading">E. I appreciated the...</label>
                        <p class="text-xs text-neutral-600 mb-2">(Feedback: for management and services of HRD.)</p>
                        <textarea required
                            name="appreciation"
                            id="appreciation"
                            rows="4"
                            class="mt-1 bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body"
                            placeholder="Enter text here">{{ old('appreciation') }}</textarea>
                        @error('appreciation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="relative h-fit overflow-hidden rounded-xl">
                <div class="flex flex-col justify-end items-end gap-2 p-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 transition-colors">
                        Submit Learning Journal
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts::app>
