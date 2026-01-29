<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'title',
        'hours',
        'datestart',
        'dateend',
        'venue',
        'conductedby',
        'registration_fee',
        'travel_expenses',
        'topics',
        'insights',
        'application',
        'challenges',
        'appreciation',
    ];

    protected $casts = [
        'datestart' => 'date',
        'dateend' => 'date',
        'hours' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for formatted date range
    public function getDateRangeAttribute()
    {
        if ($this->datestart && $this->dateend) {
            return $this->datestart->format('F d, Y') . ' - ' . $this->dateend->format('F d, Y');
        }
        return $this->datestart?->format('F d, Y') ?? 'N/A';
    }
}