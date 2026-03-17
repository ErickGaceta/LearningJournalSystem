<?php

namespace App\Console\Commands;

use App\Mail\NotifMail;
use App\Models\Assignment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTrainingCompletionNotifications extends Command
{
    protected $signature   = 'trainings:notify-completed';
    protected $description = 'Send email notifications for completed trainings';

    public function handle()
    {
        $assignments = Assignment::with(['user', 'module'])
            ->whereHas('module', function ($q) {
                $q->whereDate('dateend', today());
            })
            ->where(function ($q) {
                $q->whereHas('user', function ($userQuery) {
                    $userQuery->whereDoesntHave('documents', function ($docQuery) {
                        $docQuery->whereColumn('documents.module_id', 'assignments.module_id')
                            ->where('isArchived', false);
                    });
                });
            })
            ->get();

        if ($assignments->isEmpty()) {
            $this->info('No users to notify.');
            return;
        }

        foreach ($assignments as $assignment) {
            if (!$assignment->user || !$assignment->user->email) {
                continue;
            }

            Mail::to($assignment->user->email)->send(new NotifMail(
                mailSubject: 'Your Training is Complete — Submit Your Learning Journal',
                body: $assignment->user->first_name,
                module: $assignment->module,
            ));

            $this->line("Notified: {$assignment->user->email}");
        }

        $this->info("Notified {$assignments->count()} users.");
    }
}
