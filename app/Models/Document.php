<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
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
        'hours' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}