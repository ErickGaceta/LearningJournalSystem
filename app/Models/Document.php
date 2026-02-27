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
        'travel_expenses',
        'topics',
        'insights',
        'application',
        'challenges',
        'appreciation',
        'printedAt',
        'printCount',
        'isPrinted',    
        'module_id',
        'is_archived',
    ];

    protected $casts = [
        'printedAt' => 'date',
        'isPrinted' => 'integer',
    ];

    protected static function boot() {
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

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function getDateRangeAttribute()
    {
        if ($this->datestart && $this->dateend) {
            return $this->datestart->format('F d, Y') . ' - ' . $this->dateend->format('F d, Y');
        }
        return $this->datestart?->format('F d, Y') ?? 'N/A';
    }
}
