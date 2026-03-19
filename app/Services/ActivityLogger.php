<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public static function log(string $action, string $description, ?Model $model = null): void
    {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model'       => $model ? class_basename($model) : null,
            'model_id'    => $model?->getKey(),
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}