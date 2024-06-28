<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'episode_number',
        'name',
        'overview',
        'air_date',
        'still_path',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
