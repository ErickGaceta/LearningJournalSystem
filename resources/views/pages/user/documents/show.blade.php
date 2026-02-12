<x-layouts::app :title="__('View Learning Journal')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="{ editing: false }">

        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <flux:heading size="xl">Learning Journal</flux:heading>
                <flux:subheading>Submitted {{ $document->created_at->format('F d, Y') }}</flux:subheading>
            </div>
            <div class="flex gap-2">
                <!-- Toggle Edit / Cancel -->
                <flux:button
                    x-show="!editing"
                    x-on:click="editing = true"
                    variant="ghost"
                    icon="pencil">
                    Edit
                </flux:button>
                <flux:button
                    x-show="editing"
                    x-on:click="editing = false"
                    variant="ghost"
                    icon="x-mark">
                    Cancel
                </flux:button>
                <flux:button
                    href="{{ route('user.documents.index') }}"
                    variant="ghost"
                    icon="arrow-left"
                    wire:navigate>
                    Back
                </flux:button>
            </div>
        </div>

        <form action="{{ route('user.documents.update', $document) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Info Cards (always read-only) -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">

                <!-- Personal Information -->
                <flux:card>
                    <div class="flex items-center justify-center mb-4">
                        <flux:heading>Personal Information</flux:heading>
                    </div>
                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>Name</flux:label>
                            <flux:input type="text" value="{{ $document->fullname }}" readonly />
                        </flux:field>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Department/Unit/Office</flux:label>
                                <flux:input type="text" value="{{ $document->user->divisionUnit->division_units ?? 'N/A' }}" readonly />
                            </flux:field>
                            <flux:field>
                                <flux:label>Position</flux:label>
                                <flux:input type="text" value="{{ $document->user->position->positions ?? 'N/A' }}" readonly />
                            </flux:field>
                        </div>
                    </div>
                </flux:card>

                <!-- L&D Program Information -->
                <flux:card>
                    <div class="flex items-center justify-center mb-4">
                        <flux:heading>L&D Program Information</flux:heading>
                    </div>
                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>L&D Title</flux:label>
                            <flux:input type="text" value="{{ $document->module?->title ?? 'N/A' }}" readonly />
                        </flux:field>
                        <flux:field>
                            <flux:label>Number of L&D Hours</flux:label>
                            <flux:input type="text" value="{{ $document->module?->hours ?? 'N/A' }}" readonly />
                        </flux:field>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Date Started</flux:label>
                                <flux:input type="text" value="{{ $document->module?->datestart?->format('M d, Y') ?? 'N/A' }}" readonly />
                            </flux:field>
                            <flux:field>
                                <flux:label>Date Ended</flux:label>
                                <flux:input type="text" value="{{ $document->module?->dateend?->format('M d, Y') ?? 'N/A' }}" readonly />
                            </flux:field>
                        </div>
                    </div>
                </flux:card>

                <!-- L&D Additional Information -->
                <flux:card>
                    <div class="flex items-center justify-center mb-4">
                        <flux:heading>L&D Additional Information</flux:heading>
                    </div>
                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>Venue</flux:label>
                            <flux:input type="text" value="{{ $document->module?->venue ?? 'N/A' }}" readonly />
                        </flux:field>
                        <flux:field>
                            <flux:label>Conducted/Sponsored By</flux:label>
                            <flux:input type="text" value="{{ $document->module?->conductedby ?? 'N/A' }}" readonly />
                        </flux:field>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Registration Fee</flux:label>
                                <flux:input type="text" value="{{ $document->module?->registration_fee ?? 'N/A' }}" readonly />
                            </flux:field>
                            <flux:field>
                                <flux:label>Travel Expenses</flux:label>
                                <!-- Read-only view -->
                                <flux:input
                                    x-show="!editing"
                                    type="text"
                                    value="{{ $document->travel_expenses ?? 'N/A' }}"
                                    readonly />
                                <!-- Editable -->
                                <flux:input
                                    x-show="editing"
                                    type="text"
                                    name="travel_expenses"
                                    value="{{ old('travel_expenses', $document->travel_expenses) }}" />
                                <flux:error name="travel_expenses" />
                            </flux:field>
                        </div>
                    </div>
                </flux:card>
            </div>

            <!-- Journal Content -->
            <flux:card>
                <div class="space-y-6">

                    <flux:field>
                        <flux:label>A. I learned the following from the L&D program I attended...</flux:label>
                        <flux:description>Knowledge, skills, attitude, information. Please indicate the topic/topics</flux:description>
                        <flux:textarea
                            x-bind:readonly="!editing"
                            name="topics"
                            rows="4">{{ old('topics', $document->topics) }}</flux:textarea>
                        <flux:error name="topics" />
                    </flux:field>

                    <flux:field>
                        <flux:label>B. I gained the following insights and discoveries...</flux:label>
                        <flux:description>Understanding, perception, awareness</flux:description>
                        <flux:textarea
                            x-bind:readonly="!editing"
                            name="insights"
                            rows="4">{{ old('insights', $document->insights) }}</flux:textarea>
                        <flux:error name="insights" />
                    </flux:field>

                    <flux:field>
                        <flux:label>C. I will apply the new learnings in my current function by doing the following...</flux:label>
                        <flux:textarea
                            x-bind:readonly="!editing"
                            name="application"
                            rows="4">{{ old('application', $document->application) }}</flux:textarea>
                        <flux:error name="application" />
                    </flux:field>

                    <flux:field>
                        <flux:label>D. I was challenged most on...</flux:label>
                        <flux:textarea
                            x-bind:readonly="!editing"
                            name="challenges"
                            rows="4">{{ old('challenges', $document->challenges) }}</flux:textarea>
                        <flux:error name="challenges" />
                    </flux:field>

                    <flux:field>
                        <flux:label>E. I appreciated the...</flux:label>
                        <flux:description>Feedback: for management and services of HRD.</flux:description>
                        <flux:textarea
                            x-bind:readonly="!editing"
                            name="appreciation"
                            rows="4">{{ old('appreciation', $document->appreciation) }}</flux:textarea>
                        <flux:error name="appreciation" />
                    </flux:field>

                </div>
            </flux:card>

            <!-- Save Button (only visible when editing) -->
            <div class="flex justify-end" x-show="editing">
                <flux:button icon="arrow-up-on-square" type="submit" variant="primary" color="sky">
                    Save Changes
                </flux:button>
            </div>

        </form>
    </div>
</x-layouts::app>