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
        'isPrinted',
        'printedAt',
    ];

    protected $casts = [
        'datestart' => 'date',
        'dateend' => 'date',
        'hours' => 'integer',
        'printedAt' => 'date',
        'isPrinted' => 'integer',
    ];

    // Add this boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if ($document->user) {
                $document->fullname = trim(
                    $document->user->first_name . ' ' .
                        $document->user->middle_name . ' ' .
                        $document->user->last_name
                );
            }
            if (!isset($document->isPrinted)) {
                $document->isPrinted = 0;
            }
        });

        static::updating(function ($document) {
            if ($document->user) {
                $document->fullname = trim(
                    $document->user->first_name . ' ' .
                        $document->user->middle_name . ' ' .
                        $document->user->last_name
                );
            }
        });
    }

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
