<x-layouts::app :title="__('User Management - DOST LJS')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="flex justify-between">
            <div>
                <flux:heading size="xl">User Profiles and Management</flux:heading>
            </div>
            <div>
                <flux:button icon="user-plus" variant="primary" color="sky" :href="route('admin.users.create')">Add User</flux:button>
            </div>
        </div>

        <div>
            <flux:table :paginate="$users">
                <flux:table.columns>
                    <flux:table.column>Employee ID</flux:table.column>
                    <flux:table.column>Full Name</flux:table.column>
                    <flux:table.column>Position</flux:table.column>
                    <flux:table.column>Division/ Unit</flux:table.column>
                    <flux:table.column>Employee Type</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Role</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                @forelse($users as $user)
                <flux:table.rows>
                    <flux:table.row>
                        <flux:table.cell>{{ $user->employee_id }}</flux:table.cell>
                        <flux:table.cell>{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }}</flux:table.cell>
                        <flux:table.cell>{{ $user->position->positions }}</flux:table.cell>
                        <flux:table.cell>{{ $user->divisionUnit->division_units }}</flux:table.cell>
                        <flux:table.cell>{{ $user->employee_type }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>{{ $user->user_type === 'hr' ? 'HR' : ucfirst($user->user_type) }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button variant="ghost" icon="pencil-square" :href="route('admin.users.edit', $user)"></flux:button>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <flux:button
                                    type="submit"
                                    variant="ghost"
                                    icon="trash"
                                    onclick="return confirm('Delete this user?')" />
                            </form>
                        </flux:table.cell>
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

    </div>
</x-layouts::app>