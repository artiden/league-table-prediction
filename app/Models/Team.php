<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    const AVERAGE_STRENGTH = 50;
    const STRENGTH_DEVIATION = 5;
    use HasFactory;
    const DEFAULT_VALUES = [
        'played' => 0,
        'won' => 0,
        'drawn' => 0,
        'lost' => 0,
        'goals_for' => 0,
        'goals_against' => 0,
        'goal_difference' => 0,
        'points' => 0,
    ];

    protected $fillable = [
        'name',
        'strength',
        'played',
        'won',
        'drawn',
        'lost',
        'goals_for',
        'goals_against',
        'goal_difference',
        'points',
    ];
}
