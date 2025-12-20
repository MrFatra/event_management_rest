<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSpeaker extends Model
{
    protected $fillable = [
        'event_id',
        'speaker_id',
    ];

    public function speaker()
    {
        return $this->belongsTo(Speaker::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
