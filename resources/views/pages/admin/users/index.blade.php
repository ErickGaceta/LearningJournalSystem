<x-layouts::app :title="__('User Management - DOST LJS')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        @if(session('success'))
        <div class="rounded-lg border {{ str_contains(session('success'), 'Temporary password') ? 'bg-green-50 border-green-300' : 'bg-blue-50 border-blue-300' }} p-4">
            <div class="flex items-start gap-3">
                <flux:icon.check-circle class="size-5 text-green-600 mt-0.5" />
                <div class="flex-1">
                    <p class="font-medium text-green-900">{{ session('success') }}</p>

                    @if(str_contains(session('success'), 'Temporary password'))
                    @php
                    preg_match('/Temporary password: (.+)$/', session('success'), $matches);
                    $password = $matches[1] ?? '';
                    @endphp

                    @if($password)
                    <div class="mt-3 p-3 bg-white border border-green-200 rounded-md">
                        <p class="text-xs font-semibold text-gray-700 mb-1">Temporary Password (save this!):</p>
                        <div class="flex items-center gap-2">
                            <code class="flex-1 text-lg font-mono text-green-800 select-all">{{ $password }}</code>
                            <flux:button
                                size="sm"
                                variant="ghost"
                                onclick="navigator.clipboard.writeText('{{ $password }}'); this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy', 2000)">
                                Copy
                            </flux:button>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">⚠️ This password will not be shown again. The user must change it on first login.</p>
                    </div>
                    @endif
                    @endif
                </div>
                <button type="button" onclick="this.closest('div').parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                    <flux:icon.x-mark class="size-5" />
                </button>
            </div>
        </div>
        @endif

        <div class="flex justify-between">
            <div>
                <flux:heading size="xl">Admin Profiles</flux:heading>
            </div>
        </div>

        <div>

            @foreach($admins as $admin)
            <flux:card class="bg-transparent border-zinc-500 flex flex-col gap-2">
                <div class="flex justify-between align-center items-center">
                    <flux:heading size="lg">{{ $admin->first_name . ' ' . $admin->middle_name . ' ' . $admin->last_name }}</flux:heading>
                    <flux:button variant="ghost" icon="eye" :href="route('admin.users.show', $admin)"></flux:button>
                </div>
                <flux:separator />
                <div class="flex gap-3">
                    <flux:text>Employee ID:</flux:text>
                    <flux:text>{{ $admin->employee_id }}</flux:text>
                </div>
                <div class="flex gap-3">
                    <flux:text>Position:</flux:text>
                    <flux:text>{{ $admin->position->positions }}</flux:text>
                </div>
                <div class="flex gap-3">
                    <flux:text>Division/ Unit:</flux:text>
                    <flux:text>{{ $admin->divisionUnit->division_units }}</flux:text>
                </div>
                <div class="flex gap-3">
                    <flux:text>Email:</flux:text>
                    <flux:text>{{ $admin->email }}</flux:text>
                </div>
            </flux:card>
            @endforeach
        </div>

        <div class="flex justify-between">
            <div>
                <flux:heading size="xl">User Management</flux:heading>
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

                <flux:table.rows>
                    @forelse($users as $user)
                    <flux:table.row>
                        <flux:table.cell>{{ $user->employee_id }}</flux:table.cell>
                        <flux:table.cell>{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }}</flux:table.cell>
                        <flux:table.cell>{{ $user->position->positions }}</flux:table.cell>
                        <flux:table.cell>{{ $user->divisionUnit->division_units }}</flux:table.cell>
                        <flux:table.cell>{{ $user->employee_type }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>{{ $user->user_type === 'hr' ? 'HR' : ucfirst($user->user_type) }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:button variant="ghost" icon="eye" :href="route('admin.users.show', $user)"></flux:button>
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
                    @empty
                    <flux:table.row class="col-span-8 text-center">
                        <flux:table.cell>No users in the database</flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

    </div>
</x-layouts::app>