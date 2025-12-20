<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    protected $fillable = [
        'name',
        'bio',
        'photo',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_speakers');
    }
}
