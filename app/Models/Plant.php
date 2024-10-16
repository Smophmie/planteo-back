<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'type',
        'description',
        'sowing_period',
        'planting_period',
        'harvest_period',
        'soil',
        'watering',
        'exposure',
        'maintenance',
    ];

    public function favoritedBy(): BelongsToMany
        {
            return $this->belongsToMany(User::class, 'favorites');
        }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
