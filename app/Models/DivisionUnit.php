<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionUnit extends Model
{
    use HasFactory;

    protected $fillable = ['division_units'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_division_units');
    }
}