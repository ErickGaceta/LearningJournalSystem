@props(['module'])

<flux:modal name="edit-module-{{ $module->id }}" class="max-w-lg">
    <div x-data="{ editing: false }">
        <form action="{{ route('hr.modules.update', $module) }}" method="POST" class="flex flex-col gap-0">
            @csrf
            @method('PUT')

            <div class="p-6 bg-white dark:bg-neutral-800 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="lg">Training Module</flux:heading>
                        <flux:text variant="subtle" class="text-sm mt-1">{{ $module->title }}</flux:text>
                    </div>
                    <div class="flex gap-2">
                        <flux:button
                            x-show="!editing"
                            x-on:click="editing = true"
                            variant="ghost"
                            size="sm"
                            icon="pencil">
                            Edit
                        </flux:button>
                        <flux:button
                            x-show="editing"
                            x-on:click="editing = false"
                            variant="ghost"
                            size="sm"
                            icon="x-mark">
                            Cancel
                        </flux:button>
                    </div>
                </div>

                <flux:separator />

                <div class="flex flex-col gap-3">
                    <flux:heading size="sm">Basic Information</flux:heading>
                    <flux:input
                        x-bind:readonly="!editing"
                        name="title"
                        label="Training Title"
                        required
                        value="{{ old('title', $module->title) }}" />
                    <flux:input
                        x-bind:readonly="!editing"
                        name="venue"
                        label="Venue"
                        required
                        value="{{ old('venue', $module->venue) }}" />
                    <flux:input
                        x-bind:readonly="!editing"
                        name="conductedby"
                        label="Conducted / Sponsored By"
                        required
                        value="{{ old('conductedby', $module->conductedby) }}" />
                </div>

                <flux:separator />

                <div class="flex flex-col gap-3">
                    <flux:heading size="sm">Additional Information</flux:heading>
                    <div class="grid grid-cols-2 gap-3">
                        <flux:input
                            x-bind:readonly="!editing"
                            name="datestart"
                            type="date"
                            label="Date Start"
                            required
                            value="{{ old('datestart', optional($module->datestart)->format('Y-m-d')) }}" />
                        <flux:input
                            x-bind:readonly="!editing"
                            name="dateend"
                            type="date"
                            label="Date End"
                            required
                            value="{{ old('dateend', optional($module->dateend)->format('Y-m-d')) }}" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <flux:input
                            x-bind:readonly="!editing"
                            name="hours"
                            label="Training Hours"
                            required
                            value="{{ old('hours', $module->hours) }}" />
                        <flux:input
                            x-bind:readonly="!editing"
                            name="registration_fee"
                            label="Registration Fee"
                            placeholder="N/A if free"
                            value="{{ old('registration_fee', $module->registration_fee) }}" />
                    </div>
                </div>
            </div>

            <div
                x-show="editing"
                class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm" class="flex-1">Close</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" color="teal" size="sm" icon="folder-plus" class="flex-1">
                    Update
                </flux:button>
            </div>

            {{-- Footer when not editing --}}
            <div
                x-show="!editing"
                class="bg-white dark:bg-neutral-800 px-6 py-3 flex justify-end border-t border-neutral-200 dark:border-neutral-700">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm">Close</flux:button>
                </flux:modal.close>
            </div>
        </form>
    </div>
</flux:modal>