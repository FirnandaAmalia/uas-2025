<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Get the votes for the idea.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Accessor to get the vote count.
     *
     * @return int
     */
    public function getVoteCountAttribute(): int
    {
        return $this->votes()->count();
    }
}
