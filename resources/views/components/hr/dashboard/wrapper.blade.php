@props(['modules', 'totalModules', 'activeTraining', 'usersInTraining'])

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <x-hr.dashboard.welcome />
    <x-hr.dashboard.stats-card
        :totalModules="$totalModules"
        :activeTraining="$activeTraining"
        :usersInTraining="$usersInTraining" />
    <x-hr.dashboard.desktop-table :modules="$modules" />
    <x-hr.dashboard.mobile-cards :modules="$modules" />
</div>