<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
        protected $table = 'teams';

    protected $fillable = [
        'teamName',
        'teamLogo',
        'adminId',
    ];

    // Relationship: a team has many players
    public function players(): HasMany
    {
        return $this->hasMany(TeamPlayer::class, 'team_id');
    }

    // Relationship: team belongs to an admin (user)
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adminId', 'userId');
    }

}
