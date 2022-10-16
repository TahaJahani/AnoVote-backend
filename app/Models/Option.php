<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    use HasFactory;

    public $fillable = [
        "poll_id", "text"
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
