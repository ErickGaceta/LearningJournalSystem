<?php

namespace App\Notifications;

use App\Models\TrainingModule;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ModuleSubmissionReminder extends Notification
{   
    public function __construct(public TrainingModule $module) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: Submit Your Learning Journal')
            ->view('emails.notif', [
                'body'   => $notifiable->first_name,
                'module' => $this->module,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'   => 'Please submit your Learning Journal for: ' . $this->module->title,
            'module_id' => $this->module->id,
            'module'    => $this->module->title,
            'url'       => route('user.documents.index'),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}