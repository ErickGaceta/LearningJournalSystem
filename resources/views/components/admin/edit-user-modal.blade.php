@props(['positions', 'divisions'])

@php
$positionOptions = $positions->map(fn($p) => "<option value=\"{$p->id}\">{$p->positions}</option>")->implode('');
$divisionOptions = $divisions->map(fn($d) => "<option value=\"{$d->id}\">{$d->division_units}</option>")->implode('');

$inputClass = 'w-full rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-black dark:text-white px-3 py-1.5 text-sm shadow-sm placeholder:text-zinc-400 dark:placeholder:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-sky-500 disabled:opacity-50 read-only:bg-zinc-50 dark:read-only:bg-zinc-900 read-only:text-zinc-500';
$selectClass = 'w-full rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 text-black dark:text-white px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 disabled:opacity-50 disabled:bg-zinc-50 dark:disabled:bg-zinc-900';
@endphp

<flux:modal name="shared-edit-user" style="width: 75vw; max-width: 75vw;">
    <div x-data="{ editing: false }" x-on:flux-modal.opened.window="editing = false" class="w-full">

        <form :action="'{{ route('admin.users.update', '_placeholder_') }}'.replace('_placeholder_', selectedUser?.id)" method="POST" class="flex flex-col gap-0 w-full">
            @csrf

            <div class="p-6 bg-white dark:bg-neutral-800 space-y-4 w-full">

                {{-- Header --}}
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white"
                            x-text="selectedUser ? [selectedUser.first_name, selectedUser.middle_name, selectedUser.last_name].filter(Boolean).join(' ') : ''">
                        </h2>
                        <p class="text-sm text-zinc-500 mt-1" x-text="selectedUser?.email"></p>
                    </div>
                    <div class="flex gap-2">
                        <flux:button x-show="!editing" x-on:click="editing = true" variant="ghost" size="sm" icon="pencil">Edit</flux:button>
                        <flux:button x-show="editing" x-on:click="editing = false" variant="ghost" size="sm" icon="x-mark">Cancel</flux:button>
                    </div>
                </div>

                <flux:separator />

                <div class="grid grid-cols-1 md:grid-cols-3 w-full gap-6">

                    {{-- Personal Information --}}
                    <div class="flex flex-col gap-3">
                        <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Personal Information</p>

                        <x-admin.field label="First Name">
                            <input name="first_name" x-bind:readonly="!editing" x-bind:value="selectedUser?.first_name"
                                class="{{ $inputClass }}" required />
                        </x-admin.field>

                        <x-admin.field label="Middle Name">
                            <input name="middle_name" x-bind:readonly="!editing" x-bind:value="selectedUser?.middle_name"
                                class="{{ $inputClass }}" />
                        </x-admin.field>

                        <x-admin.field label="Last Name">
                            <input name="last_name" x-bind:readonly="!editing" x-bind:value="selectedUser?.last_name"
                                class="{{ $inputClass }}" required />
                        </x-admin.field>

                        <x-admin.field label="Gender">
                            <select name="gender" x-bind:disabled="!editing"
                                x-effect="if (selectedUser) $el.value = selectedUser.gender"
                                class="{{ $selectClass }}">
                                <option value="">Select gender...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </x-admin.field>
                    </div>

                    {{-- Login Information --}}
                    <div class="flex flex-col gap-3">
                        <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Login Information</p>

                        <x-admin.field label="Username">
                            <input name="username" x-bind:readonly="!editing" x-bind:value="selectedUser?.username"
                                class="{{ $inputClass }}" required />
                        </x-admin.field>

                        <x-admin.field label="Email">
                            <input name="email" type="email" x-bind:readonly="!editing" x-bind:value="selectedUser?.email"
                                class="{{ $inputClass }}" required />
                        </x-admin.field>

                        <x-admin.field label="Position">
                            <select name="id_positions" x-bind:disabled="!editing"
                                x-effect="if (selectedUser) $el.value = selectedUser.position_id"
                                class="{{ $selectClass }}">
                                <option value="">Select position...</option>
                                {!! $positionOptions !!}
                            </select>
                        </x-admin.field>

                        <x-admin.field label="Division/Unit">
                            <select name="id_division_units" x-bind:disabled="!editing"
                                x-effect="if (selectedUser) $el.value = selectedUser.division_id"
                                class="{{ $selectClass }}">
                                <option value="">Select division...</option>
                                {!! $divisionOptions !!}
                            </select>
                        </x-admin.field>
                    </div>

                    {{-- Others --}}
                    <div class="flex flex-col gap-3">
                        <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Others</p>

                        <x-admin.field label="Employee ID">
                            <input name="employee_id" x-bind:readonly="!editing" x-bind:value="selectedUser?.employee_id"
                                class="{{ $inputClass }}" required />
                        </x-admin.field>

                        <x-admin.field label="Employee Type">
                            <input name="employee_type" x-bind:readonly="!editing" x-bind:value="selectedUser?.employee_type"
                                class="{{ $inputClass }}" required />
                        </x-admin.field>

                        <x-admin.field label="User Role">
                            <select name="user_type" x-bind:disabled="!editing"
                                x-effect="if (selectedUser) $el.value = selectedUser.user_type"
                                class="{{ $selectClass }}">
                                <option value="">Select role...</option>
                                <option value="user">User</option>
                                <option value="hr">HR</option>
                            </select>
                        </x-admin.field>

                        <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                            <input type="checkbox" name="is_active" value="1"
                                x-bind:disabled="!editing"
                                x-bind:checked="selectedUser?.is_active"
                                class="rounded border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sky-600 focus:ring-sky-500 disabled:opacity-50" />
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <div x-show="editing"
                class="bg-white dark:bg-neutral-800 px-6 py-3 items-end justify-end flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm" class="flex-1">Close</flux:button>
                </flux:modal.close>
                <flux:button style="max-width: max-content;" type="submit" variant="primary" color="sky" size="sm" icon="arrow-up-on-square" class="flex-1">
                    Save Changes
                </flux:button>
            </div>

            <div x-show="!editing"
                class="bg-white dark:bg-neutral-800 px-6 py-3 flex justify-end border-t border-neutral-200 dark:border-neutral-700">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm">Close</flux:button>
                </flux:modal.close>
            </div>
        </form>

        <form
            :action="'{{ route('admin.users.resetPassword', '_placeholder_') }}'.replace('_placeholder_', selectedUser?.id)"
            method="POST">
            @csrf
            <flux:button type="submit" variant="ghost" size="sm" icon="key"
                onclick="return confirm('Generate a new temporary password for this user?')"
                class="text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/30">
                Reset Password
            </flux:button>
        </form>
    </div>
</flux:modal>