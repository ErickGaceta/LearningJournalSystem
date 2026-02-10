<x-layouts::app :title="__('Edit Training Module')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Edit Training Module</flux:heading>

        <div>
            <form action="{{ route('hr.modules.update', $module) }}" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')

                <flux:card class="flex flex-col gap-2">
                    <flux:heading align="center">L&D Basic Information</flux:heading>
                    <flux:separator />

                    <flux:input
                        name="title"
                        label="Training Title"
                        required
                        value="{{ old('title', $module->title) }}"
                    />

                    <flux:input
                        name="venue"
                        label="Venue"
                        required
                        value="{{ old('venue', $module->venue) }}"
                    />

                    <flux:input
                        name="conductedby"
                        label="Conducted/ Sponsored By"
                        required
                        value="{{ old('conductedby', $module->conductedby) }}"
                    />
                </flux:card>

                <flux:card class="flex flex-col gap-2 w-full">
                    <flux:heading align="center">L&D Additional Information</flux:heading>
                    <flux:separator />

                    <flux:input
                        name="datestart"
                        type="date"
                        label="Date Start"
                        required
                        value="{{ old('datestart', optional($module->datestart)->format('Y-m-d')) }}"
                    />

                    <flux:input
                        name="dateend"
                        type="date"
                        label="Date End"
                        required
                        value="{{ old('dateend', optional($module->dateend)->format('Y-m-d')) }}"
                    />

                    <flux:input
                        name="hours"
                        class="grow"
                        label="Training Hours"
                        required
                        value="{{ old('hours', $module->hours) }}"
                    />

                    <flux:input
                        name="registration_fee"
                        class="grow"
                        label="Registration Fee"
                        value="{{ old('registration_fee', $module->registration_fee) }}"
                    />
                </flux:card>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('hr.modules.index') }}">
                        <flux:button variant="secondary">Cancel</flux:button>
                    </a>
                    <flux:button type="submit" icon="save" variant="primary" color="teal">
                        Update
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
