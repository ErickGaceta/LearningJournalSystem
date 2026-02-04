<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingModule extends Model
{
    use HasFactory;

    protected $table = 'training_module';

    protected $fillable = [
        'title',
        'hours',
        'datestart',
        'dateend',
        'venue',
        'conductedby',
        'registration_fee',
        'travel_expenses',
    ];

    protected $casts = [
        'datestart' => 'date',
        'dateend' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'module_id');
    }

    public function getDurationInDaysAttribute(): int
    {
        return $this->datestart->diffInDays($this->dateend) + 1;
    }

    public function getDateRangeAttribute(): string
    {
        return $this->datestart->format('M d, Y') . ' - ' . $this->dateend->format('M d, Y');
    }
}