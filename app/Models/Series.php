<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'overview',
        'first_air_date',
        'poster_path',
    ];

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

}
