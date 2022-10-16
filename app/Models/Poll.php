<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    public static array $SHOW_MODES = [
        "hidden",
        "show",
        "after_finish", # After poll is finished
    ];

    public $fillable = [
        "title", "slug", "description", "access_time",
        "end_time", "show_mode", "is_multi_option"
    ];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }


    public static function create(array $attributes = []): Model|Builder
    {
        while (true) {
            try {
                $slug = bin2hex(random_bytes(16));
                $attributes['slug'] = $slug;
                $model = static::query()->create($attributes);
                break;
            } catch (Exception) {
            }
        }
        return $model;
    }

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('access_time', '<=', now())
                ->where('end_time', '>=', now());
        });
    }
}
