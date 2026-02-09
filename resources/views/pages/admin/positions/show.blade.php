<x-layouts::app :title="'Position: ' . $position->positions">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-heading">{{ $position->positions }}</h1>
                <p class="text-sm text-neutral-600">Position Details</p>
            </div>
            <div class="flex gap-2">
                <flux:button :href="route('admin.positions.edit', $position)" variant="primary" color="sky" icon="pencil" />
                <flux:button :href="route('admin.positions.index')" variant="ghost" icon="arrow-uturn-left">
                    Back to List
                </flux:button>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 p-6">
            <h2 class="text-xl font-semibold mb-4">Information</h2>
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-neutral-600">Position Name</dt>
                    <dd class="mt-1 text-sm text-heading">{{ $position->positions }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-neutral-600">Number of Users</dt>
                    <dd class="mt-1 text-sm text-heading">{{ $position->users->count() }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-neutral-600">Created At</dt>
                    <dd class="mt-1 text-sm text-heading">{{ $position->created_at->format('M d, Y h:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-neutral-600">Last Updated</dt>
                    <dd class="mt-1 text-sm text-heading">{{ $position->updated_at->format('M d, Y h:i A') }}</dd>
                </div>
            </dl>
        </div>

        @if($position->users->count() > 0)
        <div class="rounded-xl border border-neutral-200 p-6">
            <h2 class="text-xl font-semibold mb-4">Users with this Position</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Employee ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($position->users as $user)
                        <tr>
                            <td class="px-4 py-2">{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td class="px-4 py-2">{{ $user->employee_id }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-layouts::app>