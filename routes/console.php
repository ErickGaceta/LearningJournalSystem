<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('trainings:notify-completed')->dailyAt('08:00');
