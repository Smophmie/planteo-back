<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
}
