<x-layouts::app :title="__('Create Training Module')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Create Training Module</flux:heading>
        <div>
            <form action="{{ route('hr.modules.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <flux:card class="flex flex-col gap-2">
                    <flux:heading align="center">L&D Basic Information</flux:heading>
                    <flux:separator />
                    <flux:input name="title" label="Training Title" required />
                    <flux:input name="venue" label="Venue" required />
                    <flux:input name="conductedby" label="Conducted/ Sponsored By" required />
                </flux:card>

                <flux:card class="flex flex-col gap-2 w-full">
                    <flux:heading align="center">L&D Additional Information</flux:heading>
                    <flux:separator />
                    <flux:input name="datestart" type="date" label="Date Start" required />
                    <flux:input name="dateend" type="date" label="Date End" required />
                    <flux:input name="hours" class="grow" label="Training Hours" required />
                    <flux:input name="registration_fee" class="grow" label="Registration Fee" placeholder="Write N/A for free registrations" />
                </flux:card>

                <div class="flex justify-end">
                    <flux:button type="submit" icon="folder-plus" variant="primary" color="teal">Save</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
