<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    use HasFactory;
    protected $table = 'team_players';

    public $timestamps = false;

    protected $fillable = [
        'team_id',
        'userId',
        'role',
    ];

    // Relationship: team player belongs to a team
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    // Relationship: team player belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

}
