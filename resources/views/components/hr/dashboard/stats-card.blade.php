<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Total Users on Training</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $usersInTraining ?? 0 }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Total Modules / Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $totalModules ?? 0 }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Active Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $activeTraining ?? 0 }}
                </flux:text>
            </flux:card>
        </div>