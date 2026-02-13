<x-layouts::app :title="__('Edit Training Module')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="{ editing: false }">

        {{-- Display validation errors --}}
        @if($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-300 p-4">
            <div class="flex items-start gap-3">
                <svg class="size-5 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <div class="flex-1">
                    <strong class="font-medium text-red-900">Please fix the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-800">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Edit Training Module</flux:heading>
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
            </div>
        </div>

        <form action="{{ route('hr.modules.update', $module) }}" method="POST" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <flux:card class="flex flex-col gap-2">
                <flux:heading align="center">L&D Basic Information</flux:heading>
                <flux:separator />

                <div>
                    <flux:input
                        x-bind:readonly="!editing"
                        name="title"
                        label="Training Title"
                        required
                        value="{{ old('title', $module->title) }}"
                    />
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        x-bind:readonly="!editing"
                        name="venue"
                        label="Venue"
                        required
                        value="{{ old('venue', $module->venue) }}"
                    />
                    @error('venue')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        x-bind:readonly="!editing"
                        name="conductedby"
                        label="Conducted/ Sponsored By"
                        required
                        value="{{ old('conductedby', $module->conductedby) }}"
                    />
                    @error('conductedby')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </flux:card>

            <flux:card class="flex flex-col gap-2 w-full">
                <flux:heading align="center">L&D Additional Information</flux:heading>
                <flux:separator />

                <div>
                    <flux:input
                        x-bind:readonly="!editing"
                        name="datestart"
                        type="date"
                        label="Date Start"
                        required
                        value="{{ old('datestart', optional($module->datestart)->format('Y-m-d')) }}"
                    />
                    @error('datestart')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        x-bind:readonly="!editing"
                        name="dateend"
                        type="date"
                        label="Date End"
                        required
                        value="{{ old('dateend', optional($module->dateend)->format('Y-m-d')) }}"
                    />
                    @error('dateend')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        x-bind:readonly="!editing"
                        name="hours"
                        class="grow"
                        label="Training Hours"
                        required
                        value="{{ old('hours', $module->hours) }}"
                    />
                    @error('hours')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        x-bind:readonly="!editing"
                        name="registration_fee"
                        class="grow"
                        label="Registration Fee"
                        value="{{ old('registration_fee', $module->registration_fee) }}"
                    />
                    @error('registration_fee')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </flux:card>

            <div class="flex justify-end gap-2 mt-4" x-show="editing">
                <flux:button icon="folder-plus" type="submit" variant="primary" color="teal">
                    Update
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>