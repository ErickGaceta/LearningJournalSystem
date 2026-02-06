<x-layouts::app :title="__('Assigned Training')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Employee ID</flux:table.column>
                <flux:table.column>Full Name</flux:table.column>
                <flux:table.column>Position</flux:table.column>
                <flux:table.column>Division/ Unit</flux:table.column>
                <flux:table.column>Employee Type</flux:table.column>
                <flux:table.column>Email</flux:table.column>
            </flux:table.columns>

            @forelse($users as $user)
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell>{{ $user->employee_id }}</flux:table.cell>
                    <flux:table.cell>{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }}</flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
            @empty
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell class="col-span-6">No users in the database</flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
            @endforelse
        </flux:table>
    </div>
</x-layouts::app>