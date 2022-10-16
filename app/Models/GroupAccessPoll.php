<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAccessPoll extends Model
{
    use HasFactory;

    public $fillable = [
        "group_id", "poll_id"
    ];
}
