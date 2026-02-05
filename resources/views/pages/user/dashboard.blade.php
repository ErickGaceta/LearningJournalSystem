<x-layouts::app :title="__('DOST CAR Learning Journal System - User Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Welcome Section -->
        <div class="space-y-1">
            <flux:heading size="xl">Welcome, {{ auth()->user()->first_name }}!</flux:heading>
            <flux:subheading>Manage your learning journal entries</flux:subheading>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Assigned Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->count() }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Journals Created</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->sum('hours') }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Ongoing Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->whereYear('datestart', date('Y'))->count() }}
                </flux:text>
            </flux:card>
        </div>

        <!-- Create New Document Card -->
        <flux:card>
            <div class="flex flex-col items-center justify-center gap-4 py-8">
                <div class="text-center">
                    <flux:subheading>Create a new learning journal entry</flux:subheading>
                </div>

                <flux:button
                    :href="route('user.documents.create')"
                    variant="primary"
                    icon="plus">
                    Create New Learning Journal
                </flux:button>
            </div>
        </flux:card>

        <!-- Recent Documents Section (Optional) -->
        @php
        $recentDocuments = auth()->user()->documents()->latest()->take(5)->get();
        @endphp

        @if($recentDocuments->count() > 0)
            <div>
                <div class="flex items-center justify-between">
                    <flux:heading>Recent Documents</flux:heading>
                </div>
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:column>
                        <flux:table.column>Status</flux:column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($recentDocuments as $document)
                    <flux:table.row>
                        <flux:table.cell>
                            <div>
                                <flux:heading size="sm">{{ $document->title }}</flux:heading>
                                <flux:subheading class="text-xs">
                                    {{ $document->created_at->diffForHumans() }}
                                </flux:subheading>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($document->isPrinted)
                            <flux:badge color="green" size="sm">Printed</flux:badge>
                            @else
                            <flux:badge color="amber" size="sm">Pending</flux:badge>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif

    </div>
</x-layouts::app>
