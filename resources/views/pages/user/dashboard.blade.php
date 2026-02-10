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

        <!-- Training Name and Duration Section -->
        <?php
        $userTrainings = \App\Models\Document::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        // Function to determine training status
        function getTrainingStatus($training) {
            $now = \Carbon\Carbon::now();
            $startDate = $training->datestart ? \Carbon\Carbon::parse($training->datestart) : null;
            $endDate = $training->dateend ? \Carbon\Carbon::parse($training->dateend) : null;

            if (!$startDate) {
                return ['status' => 'Pending', 'color' => 'amber'];
            }

            if ($now->lt($startDate)) {
                return ['status' => 'Pending', 'color' => 'amber'];
            }

            if ($endDate && $now->gt($endDate)) {
                return ['status' => 'Finished', 'color' => 'green'];
            }

            if ($now->gte($startDate) && (!$endDate || $now->lte($endDate))) {
                return ['status' => 'Ongoing', 'color' => 'blue'];
            }

            return ['status' => 'Pending', 'color' => 'amber'];
        }
        ?>

        @if($userTrainings->count() > 0)
            <flux:card>
                <div class="space-y-4">
                    <flux:heading size="lg">Training Name and Duration</flux:heading>

                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Training Name</flux:table.column>
                            <flux:table.column>Duration (Hours)</flux:table.column>
                            <flux:table.column>Start Date</flux:table.column>
                            <flux:table.column>End Date</flux:table.column>
                            <flux:table.column>Status</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($userTrainings as $training)

                            <?php
                                $statusInfo = getTrainingStatus($training);
                            ?>endphp

                            <flux:table.row>
                                <flux:table.cell>
                                    <flux:heading size="sm">{{ $training->title }}</flux:heading>
                                </flux:table.cell>
                                <flux:table.cell>
                                    {{ $training->hours ?? 'N/A' }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    {{ $training->datestart ? \Carbon\Carbon::parse($training->datestart)->format('M d, Y') : 'N/A' }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    {{ $training->dateend ? \Carbon\Carbon::parse($training->dateend)->format('M d, Y') : 'N/A' }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge color="{{ $statusInfo['color'] }}" size="sm">
                                        {{ $statusInfo['status'] }}
                                    </flux:badge>
                                </flux:table.cell>
                            </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>

                    @if(\App\Models\Document::where('user_id', auth()->id())->count() > 5)
                        <div class="text-center">
                            <flux:button
                                :href="route('documents.index')"
                                variant="ghost"
                                size="sm">
                                View All Trainings
                            </flux:button>
                        </div>
                    @endif
                </div>
            </flux:card>


        @endif

        <!-- Training Status Overview -->
        <?php
        $pendingCount = 0;
        $ongoingCount = 0;
        $finishedCount = 0;

        $allTrainings = \App\Models\Document::where('user_id', auth()->id())->get();
        foreach($allTrainings as $training) {
            $status = getTrainingStatus($training);
            if($status['status'] === 'Pending') $pendingCount++;
            elseif($status['status'] === 'Ongoing') $ongoingCount++;
            elseif($status['status'] === 'Finished') $finishedCount++;
        }
        ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Training Name</flux:table.column>
                <flux:table.column>Duration</flux:table.column>
                <flux:table.column>Status</flux:table.column>
            </flux:table.columns>


             @forelse($userTrainings as $ut)
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell>{{ $ut->training_module?->title ?? 'N/A' }}</flux:table.cell>
                    <flux:table.cell>{{ $ut->interval?->format('%a days') ?? 'N/A' }}</flux:table.cell>
                    <flux:table.cell>

             @if($ut->appointed_at === null)
            <flux:badge color="gray" size="sm" inset="top bottom">N/A</flux:badge>
             @elseif($ut->finished_at !== null)
            <flux:badge color="green" size="sm" inset="top bottom">Completed</flux:badge>
             @else
            <flux:badge color="yellow" size="sm" inset="top bottom">Ongoing</flux:badge>
             @endif
            </flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
            @empty
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell class="col-span-6">No Trainings yet</flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
            @endforelse
            </flux:table>
            <flux:button
                    :href="route('user.documents.create')"
                    icon="plus"
                    wire:navigate>
                    Create First Journal
            </flux:button>
    </div>

    </div>
</x-layouts::app>
