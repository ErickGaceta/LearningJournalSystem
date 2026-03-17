<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('trainings:notify-completed')->dailyAt('08:00');

Schedule::call(function () {
    \App\Models\User::all()->each(function ($user) {
        $user->readNotifications()
            ->where('created_at', '<', now()->subDays(30))
            ->delete();
    });
})->daily();
