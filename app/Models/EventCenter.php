<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCenter extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'features' => 'array',
        'images_gallery' => 'array',
        'has_parking' => 'boolean',
        'has_generator' => 'boolean',
        'has_decoration' => 'boolean',
        'has_catering' => 'boolean',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function lga()
    {
        return $this->belongsTo(Lga::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
