<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    protected $fillable = [
        'title',
        'hours',
        'date',
        'venue',
        'registration_fee',
        'travel_expenses',
        'topics',
        'insights',
        'application',
        'challenges',
        'appreciation',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Boot method to auto-populate user_id and fullname
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (!$document->user_id) {
                $user = Auth::user();
                $document->user_id = $user ? $user->id : null;
            }
            if (!$document->fullname) {
                $user = Auth::user();
                $document->fullname = $user ? $user->full_name : null;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}