<x-layouts::app.sidebar :title="__('DOST CAR Learning Journal System - Create Document')">
    <flux:main>
        <form action="{{ route('documents.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Personal Information -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Personal Information Card -->
                <flux:card>
                    <div class="flex items-center justify-center">
                        <flux:heading>Personal Information</flux:heading>
                    </div>

                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>Employee ID</flux:label>
                            <flux:input
                                type="number"
                                name="employee_id"
                                value="{{ auth()->user()->employee_id }}"
                                readonly />
                        </flux:field>

                        <flux:field>
                            <flux:label>Name</flux:label>
                            <flux:input
                                type="text"
                                name="fullname"
                                value="{{ old('fullname', auth()->user()->first_name . ' ' . (auth()->user()->middle_name ? auth()->user()->middle_name . ' ' : '') . auth()->user()->last_name) }}"
                                readonly />
                            <flux:error name="fullname" />
                        </flux:field>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Department/Unit/Office</flux:label>
                                <flux:input
                                    type="text"
                                    name="division_units"
                                    value="{{ old('division_units', auth()->user()->divisionUnit->division_units ?? 'Not Assigned') }}"
                                    readonly />
                                <flux:error name="division_units" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Position</flux:label>
                                <flux:input
                                    type="text"
                                    name="positions"
                                    value="{{ old('positions', auth()->user()->position->positions ?? 'Not Assigned') }}"
                                    readonly />
                                <flux:error name="positions" />
                            </flux:field>

                        </div>
                    </div>
                </flux:card>

                <!-- L&D Program Information Card -->
                <flux:card>
                    <div class="flex items-center justify-center">
                        <flux:heading>L&D Program Information</flux:heading>
                    </div>

                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>L&D Title</flux:label>
                            <flux:input
                                type="text"
                                name="title"
                                placeholder="L&D Title"
                                value="{{ old('title') }}"
                                required />
                            <flux:error name="title" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Number of L&D Hours</flux:label>
                            <flux:input
                                type="number"
                                name="hours"
                                min="1"
                                placeholder="L&D Hours"
                                value="{{ old('hours') }}"
                                required />
                            <flux:error name="hours" />
                        </flux:field>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Date Started</flux:label>
                                <flux:input
                                    type="date"
                                    name="datestart"
                                    value="{{ old('datestart') }}"
                                    required />
                                <flux:error name="datestart" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Date Ended</flux:label>
                                <flux:input
                                    type="date"
                                    name="dateend"
                                    value="{{ old('dateend') }}"
                                    required />
                                <flux:error name="dateend" />
                            </flux:field>
                        </div>
                    </div>
                </flux:card>

                <!-- L&D Additional Information Card -->
                <flux:card>
                    <div class="flex items-center justify-center">
                        <flux:heading>L&D Additional Information</flux:heading>
                    </div>

                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>Venue</flux:label>
                            <flux:input
                                type="text"
                                name="venue"
                                placeholder="Venue"
                                value="{{ old('venue') }}"
                                required />
                            <flux:error name="venue" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Conducted/Sponsored By</flux:label>
                            <flux:input
                                type="text"
                                name="conductedby"
                                placeholder="Conducted By"
                                value="{{ old('conductedby') }}"
                                required />
                            <flux:error name="conductedby" />
                        </flux:field>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Registration Fee</flux:label>
                                <flux:input
                                    type="text"
                                    name="registration_fee"
                                    placeholder="Registration Fee"
                                    value="{{ old('registration_fee') }}"
                                    required />
                                <flux:error name="registration_fee" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Travel Expenses</flux:label>
                                <flux:input
                                    type="text"
                                    name="travel_expenses"
                                    placeholder="Travel Expenses"
                                    value="{{ old('travel_expenses') }}"
                                    required />
                                <flux:error name="travel_expenses" />
                            </flux:field>
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Main Content Area with Form -->
            <flux:card>
                <div class="space-y-6">
                    <flux:field>
                        <flux:label>A. I learned the following from the L&D program I attended...</flux:label>
                        <flux:description>Knowledge, skills, attitude, information. Please indicate the topic/topics</flux:description>
                        <flux:textarea
                            name="topics"
                            rows="4"
                            placeholder="Enter text here"
                            required>{{ old('topics') }}</flux:textarea>
                        <flux:error name="topics" />
                    </flux:field>

                    <flux:field>
                        <flux:label>B. I gained the following insights and discoveries...</flux:label>
                        <flux:description>Understanding, perception, awareness</flux:description>
                        <flux:textarea
                            name="insights"
                            rows="4"
                            placeholder="Enter text here"
                            required>{{ old('insights') }}</flux:textarea>
                        <flux:error name="insights" />
                    </flux:field>

                    <flux:field>
                        <flux:label>C. I will apply the new learnings in my current function by doing the following...</flux:label>
                        <flux:textarea
                            name="application"
                            rows="4"
                            placeholder="Enter text here"
                            required>{{ old('application') }}</flux:textarea>
                        <flux:error name="application" />
                    </flux:field>

                    <flux:field>
                        <flux:label>D. I was challenged most on...</flux:label>
                        <flux:textarea
                            name="challenges"
                            rows="4"
                            placeholder="Enter text here"
                            required>{{ old('challenges') }}</flux:textarea>
                        <flux:error name="challenges" />
                    </flux:field>

                    <flux:field>
                        <flux:label>E. I appreciated the...</flux:label>
                        <flux:description>Feedback: for management and services of HRD.</flux:description>
                        <flux:textarea
                            name="appreciation"
                            rows="4"
                            placeholder="Enter text here"
                            required>{{ old('appreciation') }}</flux:textarea>
                        <flux:error name="appreciation" />
                    </flux:field>
                </div>
            </flux:card>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <flux:button icon="arrow-up-on-square" type="submit" variant="primary" color="sky">
                    Submit Learning Journal
                </flux:button>
            </div>
        </form>
    </flux:main>
</x-layouts::app.sidebar>