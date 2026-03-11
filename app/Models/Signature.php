<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends Model
{
    use HasFactory;
    protected $table = 'signature';

    protected $fillable = ['employee_id', 'signature_path'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}