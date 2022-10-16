<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        "year", "grade", "department"
    ];

    public function users() {
        return $this->hasMany(User::class, 'group_id');
    }

    public function votes() {
        return $this->hasMany(Vote::class, 'group_id');
    }

    public function accessiblePolls() {
        return $this->belongsToMany(
            Poll::class,
            'group_access_polls',
            'group_id',
            'poll_id'
        );
    }


    public static function getGroupFromStudentNumber($number) {
        $offset = 2;
        if (str_starts_with($number, '4')) {
            $offset = 3;
        }
        $year = substr($number, 0, $offset);
        $grade = substr($number, $offset, 1);
        // TODO: change with department
        return UserGroup::where('year', $year)->where('grade', $grade)->first();
    }
}
