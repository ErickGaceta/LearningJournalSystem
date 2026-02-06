<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'id_positions',
        'id_division_units',
        'employee_type',
        'username',
        'email',
        'password',
        'last_login',
        'is_active',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];
    
    protected $with = ['position', 'divisionUnit'];

    protected $appends = ['full_name'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== Relationships ==========
    
    public function position()
    {
        return $this->belongsTo(Position::class, 'id_positions');
    }

    public function divisionUnit()
    {
        return $this->belongsTo(DivisionUnit::class, 'id_division_units');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // ========== Accessors ==========

    public function getFullNameAttribute(): string
    {
        $mi = $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)) . '.' : '';
        return trim("{$this->first_name} {$mi} {$this->last_name}");
    }

    public function initials(): string
    {
        return Str::of($this->first_name . ' ' . $this->last_name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}