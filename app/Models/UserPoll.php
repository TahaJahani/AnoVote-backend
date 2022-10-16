<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPoll extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        "user_id", "poll_id"
    ];
}
