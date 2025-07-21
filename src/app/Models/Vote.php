<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idea_id',
        'voter_name',
    ];

    /**
     * Get the idea that was voted on.
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}

