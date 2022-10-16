<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    public static array $ROLES = [
        "admin", "student"
    ];

    public $timestamps = false;

    public $fillable = [
        "password", "group_id", "role"
    ];

    public $hidden = ["password"];

    public function group() {
        return $this->belongsTo(UserGroup::class, 'group_id');
    }

    public function accessedPolls() {
        return $this->belongsToMany(Poll::class, 'user_polls');
    }
}
