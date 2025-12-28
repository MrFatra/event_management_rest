<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'event_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_online',
        'location',
        'meeting_link',
        'price',
        'poster',
    ];

    protected $casts = [
        'is_online' => 'bool',
        'event_type' => EventType::class,
        'price' => 'float',
        'average_ratings' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'event_speakers');
    }

    public function ratings()
    {
        return $this->hasMany(EventRating::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
