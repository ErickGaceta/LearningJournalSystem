<x-layouts::app :title="__('User Management - DOST LJS')">
    @php
    $positionOptions = $positions->map(fn($p) => "<option value=\"{$p->id}\">{$p->positions}</option>")->implode('');
    $divisionOptions = $divisions->map(fn($d) => "<option value=\"{$d->id}\">{$d->division_units}</option>")->implode('');

    $tableStyle = 'text-sm py-2 px-3 border-r border-zinc-900 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400';
    $selectClass = 'w-full rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-black dark:text-white px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 disabled:opacity-50 disabled:bg-zinc-50 dark:disabled:bg-zinc-900';
    @endphp
    <div
        class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
        x-data="{ selectedUser: null }">

        {{-- ── Success banner ── --}}
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
                            <flux:button size="sm" variant="ghost"
                                onclick="navigator.clipboard.writeText('{{ $password }}'); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy',2000)">
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

        {{-- ── Admin Profiles ── --}}
        <flux:heading size="xl">Admin Profiles</flux:heading>

        <div>
            @foreach($admins as $admin)
            <flux:card class="bg-transparent border-zinc-500 flex flex-col gap-2">
                <div class="flex justify-between items-center">
                    <flux:heading size="lg">{{ trim("{$admin->first_name} {$admin->middle_name} {$admin->last_name}") }}</flux:heading>
                    {{-- Plain button — no tooltip/button-or-link-pure sub-renders --}}
                    <div @click="selectedUser = {{ Js::from([
                        'id'            => $admin->id,
                        'first_name'    => $admin->first_name,
                        'middle_name'   => $admin->middle_name,
                        'last_name'     => $admin->last_name,
                        'username'      => $admin->username,
                        'email'         => $admin->email,
                        'gender'        => $admin->gender,
                        'position_id'   => $admin->id_positions,
                        'division_id'   => $admin->id_division_units,
                        'employee_id'   => $admin->employee_id,
                        'employee_type' => $admin->employee_type,
                        'user_type'     => $admin->user_type,
                        'is_active'     => (bool) $admin->is_active,
                    ]) }}">
                        <flux:modal.trigger name="shared-edit-user">
                            <button type="button" class="p-1.5 rounded-md text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 dark:hover:text-zinc-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </flux:modal.trigger>
                    </div>
                </div>
                <flux:separator />
                <div class="flex gap-3">
                    <flux:text>Employee ID:</flux:text>
                    <flux:text>{{ $admin->employee_id }}</flux:text>
                </div>
                <div class="flex gap-3">
                    <flux:text>Position:</flux:text>
                    <flux:text>{{ $admin->position?->positions ?? '—' }}</flux:text>
                </div>
                <div class="flex gap-3">
                    <flux:text>Division/ Unit:</flux:text>
                    <flux:text>{{ $admin->divisionUnit?->division_units ?? '—' }}</flux:text>
                </div>
                <div class="flex gap-3">
                    <flux:text>Email:</flux:text>
                    <flux:text>{{ $admin->email }}</flux:text>
                </div>
            </flux:card>
            @endforeach
        </div>

        {{-- ── User Management table ── --}}
        <div class="flex justify-between">
            <flux:heading size="xl">User Management</flux:heading>
            <flux:modal.trigger name="create-user">
                <flux:button size="sm" icon="user-plus" variant="primary" color="cyan">Create User</flux:button>
            </flux:modal.trigger>
        </div>

        <div>
            <table class="font-bold text-black dark:text-white w-full table-auto">
                <thead class="border-b border-zinc-900 dark:border-zinc-700">
                    <th class="pb-2">Employee ID</th>
                    <th class="pb-2">Full Name</th>
                    <th class="pb-2">Position</th>
                    <th class="pb-2">Division/ Unit</th>
                    <th class="pb-2">Employee Type</th>
                    <th class="pb-2">Email</th>
                    <th class="pb-2">Role</th>
                    <th class="pb-2">Actions</th>
                </thead>

                <tbody>
                    @forelse($users as $user)
                    <tr class="bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-500 dark:hover:bg-zinc-600">
                        <td class="{{ $tableStyle }}">{{ $user->employee_id }}</td>
                        <td class="{{ $tableStyle }}">{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }}</td>
                        <td class="{{ $tableStyle }}">{{ $user->position->positions }}</td>
                        <td class="{{ $tableStyle }}">{{ $user->divisionUnit->division_units }}</td>
                        <td class="{{ $tableStyle }}">{{ $user->employee_type }}</td>
                        <td class="{{ $tableStyle }}">{{ $user->email }}</td>
                        <td class="{{ $tableStyle }}">{{ $user->user_type === 'hr' ? 'HR' : ucfirst($user->user_type) }}</td>
                        <td class="py-2 px-3 flex items-end justify-end font-extralight">
                            {{-- Plain buttons: no flux:button sub-component chain per row --}}
                            <div class="flex items-center gap-1">
                                <div @click="selectedUser = {{ Js::from([
                                    'id'            => $user->id,
                                    'first_name'    => $user->first_name,
                                    'middle_name'   => $user->middle_name,
                                    'last_name'     => $user->last_name,
                                    'username'      => $user->username,
                                    'email'         => $user->email,
                                    'gender'        => $user->gender,
                                    'position_id'   => $user->id_positions,
                                    'division_id'   => $user->id_division_units,
                                    'employee_id'   => $user->employee_id,
                                    'employee_type' => $user->employee_type,
                                    'user_type'     => $user->user_type,
                                    'is_active'     => (bool) $user->is_active,
                                ]) }}">
                                    <flux:modal.trigger name="shared-edit-user">
                                        <button type="button" title="View / Edit"
                                            class="p-1.5 rounded-md text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 dark:hover:text-zinc-200 transition">
                                            <x-icon.eye class="size-4 text-gray-500 hover:text-gray-700" />
                                        </button>
                                    </flux:modal.trigger>
                                </div>

                                <div @click="selectedUser = {{ Js::from([
                                    'id'          => $user->id,
                                    'first_name'  => $user->first_name,
                                    'middle_name' => $user->middle_name,
                                    'last_name'   => $user->last_name,
                                    'email'       => $user->email,
                                ]) }}">
                                    <flux:modal.trigger name="shared-delete-user">
                                        <button type="button" title="Delete"
                                            class="p-1.5 rounded-md text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </flux:modal.trigger>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="py-2">
                        <td>No users in the database</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <x-pagination :paginator="$users" />
        </div>

        {{-- ── Modals — rendered exactly once each ── --}}
        <x-admin.create-user-modal :positions="$positions" :divisions="$divisions" />
        <x-admin.edit-user-modal :positions="$positions" :divisions="$divisions" />
        <x-admin.delete-user-modal />
    </div>
</x-layouts::app>