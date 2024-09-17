<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ficture extends Model
{
    use HasFactory;
    protected $fillable = [
        'week_number',
    ];

    public function teamA(): BelongsTo {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB(): BelongsTo {
        return $this->belongsTo(Team::class, 'team_b_id');
    }
}
