<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log a user action to the activity log.
     *
     * @param string $action
     *   Supported actions:
     *   - 'login'        → User logged in
     *   - 'first_login'  → User logged in for the first time
     *   - 'logout'       → User logged out
     *   - 'created'      → A record was created
     *   - 'updated'      → A record was updated
     *   - 'deleted'      → A record was deleted
     *   - 'notified'     → A notification/email was sent
     *
     * @param string $description  Human-readable summary of the action
     * @param Model|null $model    The affected Eloquent model (optional)
     *
     * Usage:
     *   ActivityLogger::log('login', 'User logged in');
     *   ActivityLogger::log('created', "Created training: {$module->title}", $module);
     *   ActivityLogger::log('deleted', "Deleted user: {$user->full_name}");
     */
    public static function log(string $action, string $description, ?Model $model = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action'      => $action,
            'model'       => $model ? class_basename($model) : null,
            'model_id'    => $model?->getKey(),
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}
